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

class Factory implements FactoryInterface
{
    /**
     * @var
     */
    private $className;

    /**
     * Factory constructor.
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    public function createNew()
    {
        return new $this->className();
    }
}
