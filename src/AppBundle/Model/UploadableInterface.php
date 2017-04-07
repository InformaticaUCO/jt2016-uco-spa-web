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

interface UploadableInterface
{
    public function getFile();

    public function setFile(string $file);

    public function getMimeType();

    public function setMimeType(string $file);

    public function getPath();

    public function setPath(string $file);

    public function getSize();

    public function setSize(int $size);
}
