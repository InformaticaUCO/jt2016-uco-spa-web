<?php
/*
 * This file is part of the consignang.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Factory;

use AppBundle\Model\OrganizationInterface;

class OrganizationFactory implements OrganizationFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * OrganizationFactory constructor.
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return OrganizationInterface
     */
    public function createNew()
    {
        return $this->factory->createNew();
    }
}
