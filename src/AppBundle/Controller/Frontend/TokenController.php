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


use AppBundle\Model\UserInterface;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TokenController
 * @package AppBundle\Controller\Backend
 */
class TokenController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @Route("/redirect", name="redirect")
     */
    public function redirectAction()
    {
        return $this->render(':default:redirect.html.twig');
    }

    /**
     * @return JsonResponse
     * @Route("/api/auth", name="new_jwt_token")
     */
    public function authAction(Request $request)
    {
        $network = $request->get('network');
        $socialToken = $request->get('socialToken');

        try {
            $client = new Client();
            $response = $client->request('POST', 'http://nginx/simplesaml/module.php/oauth2/userinfo.php', [
                'headers' => [
                    'Authorization' => 'Bearer '.$socialToken,
                ]
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }

        $content = $response->getBody()->getContents();

        /** @var UserInterface $user */
        $user = null;
        $encoder = $this->get('lexik_jwt_authentication.encoder');

        if (!$user) {
            $token = $encoder->encode(json_decode($content, true));
        } else {
            $token = $encoder->encode([
                'username' => $user->getUsername(),
            ]);
        }

        return new JsonResponse(['token' => $token]);
    }
}