<?php
/*
 * This file is part of the consignang.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Model;

use Gedmo\Timestampable\Traits\TimestampableEntity;

trait TimestampableTrait
{
    use TimestampableEntity;
}
