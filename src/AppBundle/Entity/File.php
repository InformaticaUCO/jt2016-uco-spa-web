<?php

namespace AppBundle\Entity;

use AppBundle\Model\ExpirableTrait;
use AppBundle\Model\FileInterface;
use AppBundle\Model\FolderInterface;
use AppBundle\Model\OwneableTrait;
use AppBundle\Model\ProtectableTrait;
use AppBundle\Model\ShareableTrait;
use AppBundle\Model\TimestampableTrait;
use AppBundle\Model\TraceableTrait;
use AppBundle\Model\UploadableTrait;
use AppBundle\Model\UserInterface;
use AppBundle\Util\RandomStringGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class File.
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FileRepository")
 * @ORM\Table(name="file")
 * @Gedmo\Uploadable(filenameGenerator="SHA1", callback="configureFileCallback", appendNumber=true)
 */
class File implements FileInterface
{
    use ExpirableTrait;

    use ProtectableTrait;

    use OwneableTrait;

    use ShareableTrait;

    use TimestampableTrait;

    use TraceableTrait;

    use UploadableTrait;

    /**
     * No virus detected.
     */
    const SCAN_STATUS_OK = 'scan.ok';
    /**
     * Pending to scan.
     */
    const SCAN_STATUS_PENDING = 'scan.pending';
    /**
     * Scanning failed.
     */
    const SCAN_STATUS_FAILED = 'scan.failed';
    /**
     * Virus detected.
     */
    const SCAN_STATUS_INFECTED = 'scan.infected';

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=127, unique=true)
     * @Gedmo\Slug(fields={"name"}, unique=true)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=256)
     */
    protected $scanStatus;

    /**
     * @var FolderInterface
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Model\FolderInterface", inversedBy="files")
     */
    protected $folder;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Model\UserInterface", inversedBy="files")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Blameable(on="create")
     */
    protected $owner;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Model\UserInterface", inversedBy="sharedFiles")
     * @ORM\JoinTable(name="file_shared_user")
     */
    private $sharedWithUsers;

    /**
     * File constructor.
     */
    public function __construct()
    {
        $this->salt = RandomStringGenerator::length(16);
        $this->scanStatus = self::SCAN_STATUS_PENDING;
        $this->sharedCode = RandomStringGenerator::length(16);
        $this->sharedWithUsers = new ArrayCollection();
    }

    /**
     * To string.
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return File
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return File
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return File
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return FolderInterface
     */
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * @param FolderInterface $folder
     *
     * @return File
     */
    public function setFolder(FolderInterface $folder = null)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Set original name.
     *
     * @param array $info
     */
    public function configureFileCallback(array $info)
    {
        $this->name = $info['origFileName'];
    }

    /**
     * @return string
     */
    public function getScanStatus()
    {
        return $this->scanStatus;
    }

    /**
     * @param string $scanStatus
     *
     * @return File
     */
    public function setScanStatus(string $scanStatus)
    {
        $this->scanStatus = $scanStatus;

        return $this;
    }
}
