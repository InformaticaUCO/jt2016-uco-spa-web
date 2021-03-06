<?php

/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 02/05/15
 * Time: 05:34.
 */
namespace AppBundle\Command;

use AppBundle\Entity\Organization;
use Goutte\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class DatabaseSetupCommand extends ContainerAwareCommand
{
    const URL = 'https://www.rediris.es/sir/idps.php';

    protected function configure()
    {
        $this
            ->setName('consigna:database:setup')
            ->setDescription('Initialize the database with RedIris members')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $translator = $this->getContainer()->get('translator');
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $output->writeln('Reading data...');
        $organizations = $this->scrapping();

        /** @var Organization $organization */
        foreach ($organizations as $organization) {
            if ($organization instanceof Organization
                && !$this->getContainer()->get('consigna.repository.organization')->findOneBy(['code' => $organization->getCode()])
            ) {
                $output->writeln('New organization: '.$organization);
                $manager->persist($organization);
            }
        }



        $manager->flush();

        $output->writeln('Database updated.');
    }

    private function scrapping()
    {
        $client = new Client();
        $crawler = $client->request('GET', self::URL);

        $organizations = $crawler->filter('.codigo_scroll')->each(function (Crawler $node) {
            $university = $node->filter('div > a')->getNode(1)->textContent;

            $data = $node->filter('table > tbody > tr')->each(function (Crawler $node) {
                $key = $node->filter('td')->getNode(0)->textContent;
                $value = $node->filter('td')->getNode(1)->textContent;

                return [$key => $value];
            });

            $metadata = [];
            array_walk($data, function ($element) use (&$metadata) {
                $metadata = array_merge($metadata, $element);
            });

            if (empty($metadata['sHO'])) {
                return;
            }

            $organization = new Organization();
            $organization->setName($university);
            $organization->setCode($metadata['sHO']);
            $organization->enable();

            return $organization;
        });

        return $organizations;
    }
}
