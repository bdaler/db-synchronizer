<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 01.03.2020 16:53
 */


namespace DBSynchronizer\SecondaryDB\Entity;


use DateTime;

/**
 * Class SkuStock
 * @package DBSynchronizer\SecondaryDB\Entity
 */
class SkuStock
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
     * @var Sku
     */
    private $sku;
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
     * @return SkuStock
     */
    public function setId(int $id): SkuStock
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
     * @return SkuStock
     */
    public function setCreatedAt(DateTime $createdAt): SkuStock
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
     * @return SkuStock
     */
    public function setModifiedAt(DateTime $modifiedAt): SkuStock
    {
        $this->modifiedAt = $modifiedAt;
        return $this;
    }

    /**
     * @return Sku
     */
    public function getSku(): Sku
    {
        return $this->sku;
    }

    /**
     * @param Sku $sku
     * @return SkuStock
     */
    public function setSku($sku): SkuStock
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     * @return SkuStock
     */
    public function setStock($stock): SkuStock
    {
        $this->stock = $stock;
        return $this;
    }
}