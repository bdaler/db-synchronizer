<?php


namespace DBSynchronizer\PrimaryDB\Entity;


use DateTime;

/**
 * Class Employee
 * @package Synchronizer\PrimaryDB\Entity
 */
class Employee
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
     * @return Employee
     */
    public function setId(int $id): Employee
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
     * @return Employee
     */
    public function setCreatedAt(DateTime $createdAt): Employee
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
     * @return Employee
     */
    public function setModifiedAt(DateTime $modifiedAt): Employee
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
     * @return Employee
     */
    public function setName($name): Employee
    {
        $this->name = $name;
        return $this;
    }
}