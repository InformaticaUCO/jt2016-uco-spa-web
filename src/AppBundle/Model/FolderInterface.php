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

interface FolderInterface extends
    ExpirableInterface,
    OwnableInterface,
    ProtectableInterface,
    ResourceInterface,
    ShareableInterface,
    TimestampableInterface,
    TraceableInterface
{
    public function getName();

    public function setName(string $name);

    public function getDescription();

    public function setDescription(string $description);

    public function getSlug();

    public function setSlug(string $slug);

    public function isPermanent();

    public function setPermanent(bool $permanent);

    public function getFiles();

    public function addFile(FileInterface $file);

    public function removeFile(FileInterface $file);
}
