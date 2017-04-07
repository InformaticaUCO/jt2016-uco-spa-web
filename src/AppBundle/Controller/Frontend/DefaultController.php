<?php
/*
 * This file is part of the consignang.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller\Frontend;



use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @return string
     *
     * @Route("/", name="homepage")
     */
    public function defaultAction(Request $request)
    {
        return $this->render(':default:index.html.twig', [
            'code' => $this->getParameter('oauth2_client_id'),
        ]);
    }
}