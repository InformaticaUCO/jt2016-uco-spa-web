<?php
/*
 * This file is part of the consignang.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller\Backend;


use AppBundle\Form\Type\FolderType;
use AppBundle\Model\FolderInterface;
use AppBundle\Security\Voter\ConsignaVoter;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Hateoas\Configuration\Route;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Hateoas\Representation\PaginatedRepresentation;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FolderRestController
 * @package AppBundle\Controller
 *
 * @View(serializerGroups={"Default"})
 */
class FolderController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @ApiDoc(
     *     section="Folder",
     *     description="Get all folders"
     * )
     */
    public function cgetAction(Request $request)
    {
        $limit = $request->query->getInt('limit', 10);
        $page = $request->query->getInt('page', 1);
        $sorting = $request->query->get('sorting', []);

        $foldersPager = $this->get('consigna.repository.folder')->findAllPaginated($limit, $page, $sorting);
        $pagerFactory = new PagerfantaFactory();

        return $pagerFactory->createRepresentation(
            $foldersPager,
            new Route('get_folders', ['limit' => $limit, 'page' => $page, 'sorting' => $sorting], true)
        );
    }

    /**
     * @ApiDoc(
     *     section="Folder",
     *     description="Get a folder",
     *     requirements={
     *          {
     *              "name"="slug",
     *              "dataType"="string",
     *              "requirements"="*",
     *              "description"="Folder slug"
     *          }
     *     },
     *     statusCodes={
     *          200="Returned when succesful",
     *          404="Returned when not found"
     *     }
     * )
     * @View(serializerGroups={"Default", "show"})
     */
    public function getAction(string $slug)
    {
        $folder = $this->getFolder($slug);

        return $folder;
    }

    /**
     * @ApiDoc(
     *     section="Folder",
     *     description="Get the folder shared code",
     *     requirements={
     *          {
     *              "name"="slug",
     *              "dataType"="string",
     *              "requirements"="*",
     *              "description"="Folder slug"
     *          }
     *     },
     *     statusCodes={
     *          200="Returned when succesful",
     *          403="Access denied",
     *          404="Returned when not found"
     *     }
     * )
     * @View(serializerGroups={"security"})
     */
    public function getSharedAction(string $slug)
    {
        $folder = $this->getFolder($slug);

        if (false === $this->isGranted(ConsignaVoter::OWNER, $folder)) {
            throw $this->createAccessDeniedException();
        }

        return $folder;
    }

    /**
     * @ApiDoc(
     *     section="Folder",
     *     description="Create a new folder",
     *     input="AppBundle\Form\Type\FolderType",
     *     output="AppBundle\Entity\Folder",
     *     statusCodes={
     *          200="Returned when succesful",
     *     }
     * )
     * @View(serializerGroups={"Default","security"})
     */
    public function postAction(Request $request)
    {
        $folder = $this->get('consigna.factory.folder')->createNew();

        $form = $this->createForm(FolderType::class, $folder);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('consigna.repository.folder')->add($folder);

            return $folder;
        }

        return $form->getErrors();
    }

    /**
     * @ApiDoc(
     *     section="Folder",
     *     description="Removes a folder",
     *     requirements={
     *          {
     *              "name"="slug",
     *              "dataType"="string",
     *              "requirements"="*",
     *              "description"="Folder slug"
     *          }
     *     },
     *     statusCodes={
     *          204="Returned when succesful",
     *          403="Access denied",
     *          404="Returned when not found"
     *     }
     * )
     */
    public function deleteAction(string $slug)
    {
        $folder = $this->getFolder($slug);

        if (false === $this->isGranted(ConsignaVoter::OWNER, $folder)) {
            throw $this->createAccessDeniedException();
        }

        $this->get('consigna.repository.folder')->remove($folder);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param string $slug
     * @return null|object
     */
    private function getFolder(string $slug)
    {
        $folder = $this->get('consigna.repository.folder')->findOneActiveFolderBySlug($slug);
        if (!$folder) {
            throw $this->createNotFoundException();
        }

        return $folder;
    }

}