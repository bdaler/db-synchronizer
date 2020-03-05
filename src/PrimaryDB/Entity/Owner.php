<?php


namespace DBSynchronizer\PrimaryDB\Entity;


use DateTime;

/**
 * Class Owner
 * @package Synchronizer\PrimaryDB\Entity
 */
class Owner
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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Owner
     */
    public function setId(int $id): Owner
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
     * @return Owner
     */
    public function setCreatedAt(DateTime $createdAt): Owner
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
     * @return Owner
     */
    public function setModifiedAt(DateTime $modifiedAt): Owner
    {
        $this->modifiedAt = $modifiedAt;
        return $this;
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
     * @return Owner
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}