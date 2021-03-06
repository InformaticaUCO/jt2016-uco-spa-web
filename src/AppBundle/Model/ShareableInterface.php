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

interface ShareableInterface
{
    /**
     * Get shared code.
     *
     * @return string
     */
    public function getSharedCode();

    /**
     * Set shared code.
     *
     * @param string $sharedCode
     *
     * @return $this
     */
    public function setSharedCode(string $sharedCode);

    public function getSharedWithUsers();

    public function addSharedWithUser(UserInterface $user);

    public function removeSharedWithUser(UserInterface $user);
}
