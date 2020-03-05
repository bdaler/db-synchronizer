<?php


namespace DBSynchronizer\PrimaryDB\Entity;


use DateTime;

/**
 * Class Sku
 * @package Synchronizer\PrimaryDB\Entity
 */
class Sku
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
     * @var int
     */
    private $stock;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Sku
     */
    public function setId(int $id): Sku
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
     * @return Sku
     */
    public function setCreatedAt(DateTime $createdAt): Sku
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
     * @return Sku
     */
    public function setModifiedAt(DateTime $modifiedAt): Sku
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
     * @return Sku
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     * @return Sku
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
        return $this;
    }
}