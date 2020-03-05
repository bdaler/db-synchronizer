<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 01.03.2020 16:53
 */


namespace DBSynchronizer\SecondaryDB\Entity;


use DateTime;

/**
 * Class OutletOwner
 * @package DBSynchronizer\SecondaryDB\Entity
 */
class OutletOwner
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var DateTime
     */
    private $createdAt;
    /**
     * @var DateTime
     */
    private $modifiedAt;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $ownerName;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return OutletOwner
     */
    public function setId(int $id): OutletOwner
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     * @return OutletOwner
     */
    public function setCreatedAt(DateTime $createdAt): OutletOwner
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getModifiedAt(): DateTime
    {
        return $this->modifiedAt;
    }

    /**
     * @param DateTime $modifiedAt
     * @return OutletOwner
     */
    public function setModifiedAt(DateTime $modifiedAt): OutletOwner
    {
        $this->modifiedAt = $modifiedAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return OutletOwner
     */
    public function setName($name): OutletOwner
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getOwnerName(): string
    {
        return $this->ownerName;
    }

    /**
     * @param string $ownerName
     * @return OutletOwner
     */
    public function setOwnerName($ownerName): OutletOwner
    {
        $this->ownerName = $ownerName;
        return $this;
    }
}