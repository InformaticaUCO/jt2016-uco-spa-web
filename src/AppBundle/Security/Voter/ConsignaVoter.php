<?php
/*
 * This file is part of the consignang.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Security\Voter;


use AppBundle\Model\OwnableInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ConsignaVoter extends Voter
{
    const OWNER = 'owner';

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return mixed
     */
    protected function supports($attribute, $subject)
    {
        if (!$subject instanceof OwnableInterface) {
            return false;
        }

        return in_array($attribute, [self::OWNER]);
    }

    /**
     * @param string $attribute
     * @param OwnableInterface $subject
     * @param TokenInterface $token
     * @return mixed
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (self::OWNER === $attribute) {
            return $subject->getOwner() == $user;
        }

        return false;
    }

}