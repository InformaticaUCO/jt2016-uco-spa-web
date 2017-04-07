<?php
/*
 * This file is part of the consignang.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateTokenCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('consigna:security:token')
            ->setDescription('Create a new user token')
            ->addArgument('username', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');

        $token = $this->getContainer()->get('lexik_jwt_authentication.encoder')->encode([
            'username' => $username,
        ]);

        $output->writeln("New JWT token for ".$username.":");
        $output->writeln("Bearer ".$token);
    }
}