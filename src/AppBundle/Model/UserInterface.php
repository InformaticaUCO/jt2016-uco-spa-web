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

use FOS\UserBundle\Model\UserInterface as BaseUserInterface;

interface UserInterface extends
    BaseUserInterface,
    ResourceInterface
{
    public function getOrganization();

    public function setOrganization(OrganizationInterface $organization = null);

    public function getFiles();

    public function addFile(FileInterface $file);

    public function removeFile(FileInterface $file);

    public function getFolders();

    public function addFolder(FolderInterface $folder);

    public function removeFolder(FolderInterface $folder);

    public function getSharedFiles();

    public function addSharedFile(FileInterface $sharedFile);

    public function removeSharedFile(FileInterface $sharedFile);

    public function getSharedFolders();

    public function addSharedFolder(FolderInterface $sharedFolder);

    public function removeSharedFolder(FolderInterface $sharedFolder);
}
