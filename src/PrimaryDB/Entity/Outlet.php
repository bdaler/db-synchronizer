<?php


namespace DBSynchronizer\PrimaryDB\Entity;


use DateTime;

/**
 * Class Outlet
 * @package Synchronizer\PrimaryDB\Entity
 */
class Outlet
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
     * @var Owner
     */
    private $owner;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Outlet
     */
    public function setId(int $id): Outlet
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
     * @return Outlet
     */
    public function setCreatedAt(DateTime $createdAt): Outlet
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
     * @return Outlet
     */
    public function setModifiedAt(DateTime $modifiedAt): Outlet
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
     * @return Outlet
     */
    public function setName($name): Outlet
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Owner
     */
    public function getOwner(): Owner
    {
        return $this->owner;
    }

    /**
     * @param Owner $owner
     * @return Outlet
     */
    public function setOwner($owner): Outlet
    {
        $this->owner = $owner;
        return $this;
    }
}