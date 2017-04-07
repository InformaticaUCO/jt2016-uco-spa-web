<?php
/*
 * This file is part of the consignang.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\EventListener\Doctrine;

use AppBundle\Model\ProtectableInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class ProtectableInterfaceListener implements EventSubscriber
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * ProtectableInterfaceListener constructor.
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @return mixed
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof ProtectableInterface) {
            $this->updateUserFields($object);
        }
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof ProtectableInterface) {
            $this->updateUserFields($object);
        }
    }

    protected function updateUserFields(ProtectableInterface $object)
    {
        if (0 !== strlen($password = $object->getPlainPassword())) {
            $encoder = $this->encoderFactory->getEncoder($object);
            $object->setPassword($encoder->encodePassword($password, $object->getSalt()));
            $object->eraseCredentials();
        }
    }
}
