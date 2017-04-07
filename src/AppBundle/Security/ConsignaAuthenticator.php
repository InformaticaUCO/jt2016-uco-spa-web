<?php
/*
 * This file is part of the nodos.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Security;

use AppBundle\Util\RandomStringGenerator;
use Doctrine\Common\Persistence\ObjectManager;
use Sgomez\Bundle\SSPGuardBundle\Security\Authenticator\SSPGuardAuthenticator;
use Sgomez\Bundle\SSPGuardBundle\SimpleSAMLphp\AuthSourceRegistry;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ConsignaAuthenticator extends SSPGuardAuthenticator implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->router->generate('login'));
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $user = $userProvider->loadUserByUsername($credentials['uid'][0]);
        } catch (UsernameNotFoundException $e) {
            $user = $this->container->get('consigna.factory.user')->createNewFromOrganization($credentials['sHO'][0]);
            $user->setUsername($credentials['mail'][0]);
            $user->setEmail($credentials['mail'][0]);
            $user->setEnabled(true);
            $user->setPlainPassword(RandomStringGenerator::length(16));

            $this->container->get('doctrine.orm.entity_manager')->persist($user);
            $this->container->get('doctrine.orm.entity_manager')->flush();
        }

        return $user;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $this->saveAuthenticationErrorToSession($request, $exception);

        return new RedirectResponse($this->router->generate('login'));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetPath = $this->getTargetPath($request, $providerKey);

        if (!$targetPath) {
            // Change it to your default target
            $targetPath = $this->router->generate('homepage');
        }

        return new RedirectResponse($targetPath);
    }
}
