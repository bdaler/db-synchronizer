<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 02.03.2020 15:31
 */

namespace DBSynchronizer\Service;


use DateTime;
use DBSynchronizer\PrimaryDB\Entity\Sku as SkuPrimary;
use DBSynchronizer\SecondaryDB\Entity\Sku as SkuSecondary;
use DBSynchronizer\SecondaryDB\Entity\SkuStock as SkuStockSecondary;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;

class UpdateSku extends BaseUpdater
{
    /**
     * @param EntityManager $readFromDB
     * @param EntityManager $needUpdateDB
     * @param DateTime|null $lastSyncDate
     * @return mixed|void
     * @throws ORMException
     */
    public function updateSecondaryDb(EntityManager $readFromDB, EntityManager $needUpdateDB, ?DateTime $lastSyncDate)
    {
        $this->logger->info("start updating SKU {$needUpdateDB->getConnection()->getDatabase()} DB");
        $sku = $readFromDB->getRepository(SkuPrimary::class)->findAll($lastSyncDate);
        foreach ($sku as $item) {
            /** @var SkuPrimary $item */
            $secondarySku = new SkuSecondary();
            $secondarySku
                ->setCreatedAt($item->getCreatedAt())
                ->setModifiedAt($item->getModifiedAt())
                ->setName($item->getName());

            $skuStock = new SkuStockSecondary();
            $skuStock
                ->setStock($item->getStock())
                ->setSku($secondarySku)
                ->setModifiedAt($item->getModifiedAt())
                ->setCreatedAt($item->getCreatedAt());

            $needUpdateDB->persist($secondarySku);
            $needUpdateDB->persist($skuStock);
        }
        $this->logger->info("end updating SKU {$needUpdateDB->getConnection()->getDatabase()} DB");
    }

    /**
     * @param EntityManager $readFromDB
     * @param EntityManager $needUpdateDB
     * @param DateTime|null $lastSyncDate
     * @return mixed|void
     * @throws ORMException
     */
    public function updatePrimaryDb(EntityManager $readFromDB, EntityManager $needUpdateDB, ?DateTime $lastSyncDate)
    {
        $this->logger->info("start updating SKU {$needUpdateDB->getConnection()->getDatabase()} DB");
        $skuFromDb2 = $readFromDB->getRepository(SkuSecondary::class)->findAll();
        foreach ($skuFromDb2 as $item) {
            $newSku = new SkuPrimary();
            $newSku
                ->setName($item->getName())
                ->setCreatedAt($item->getCreatedAt())
                ->setModifiedAt($item->getModifiedAt());

            $needUpdateDB->persist($newSku);
        }
        $this->logger->info("end updating SKU {$needUpdateDB->getConnection()->getDatabase()} DB");
    }
}