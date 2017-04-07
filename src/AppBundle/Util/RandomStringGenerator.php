<?php
/*
 * This file is part of the consignang.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Util;

class RandomStringGenerator
{
    public static function length(int $length)
    {
        return base64_encode(bin2hex(openssl_random_pseudo_bytes($length)));
    }
}
