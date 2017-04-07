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

use AppBundle\Model\ExpirableInterface;
use AppBundle\Model\FileInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class ExpirableInterfaceListener implements EventSubscriber
{
    /**
     * @var
     */
    private $expiresAt;

    /**
     * ExpirableInterfaceListener constructor.
     */
    public function __construct($expiresAt)
    {
        $this->expiresAt = new \DateTime($expiresAt);
    }

    /**
     * @return mixed
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if ($object instanceof ExpirableInterface) {
            if ($object instanceof FileInterface && $object->getFolder()) {
                $object->setExpiresAt(null);
            } else {
                $object->setExpiresAt($this->expiresAt);
            }
        }
    }
}
