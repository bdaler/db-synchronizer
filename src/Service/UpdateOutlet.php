<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 02.03.2020 15:28
 */

namespace DBSynchronizer\Service;


use DateTime;
use DBSynchronizer\PrimaryDB\Entity\Outlet as OutletPrimary;
use DBSynchronizer\PrimaryDB\Entity\Owner;
use DBSynchronizer\SecondaryDB\Entity\OutletOwner as OutletOwnerSecondary;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;

class UpdateOutlet extends BaseUpdater
{
    /**
     * @param EntityManager $readFromDB
     * @param EntityManager $needUpdateDB
     * @param DateTime|null $lastSyncDate
     * @return mixed|void
     * @throws ORMException
     */
    public function updatePrimaryDb(EntityManager $readFromDB, EntityManager $needUpdateDB, ?DateTime $lastSyncDate)
    {
        $this->logger->info("start updating Outlet {$needUpdateDB->getConnection()->getDatabase()} DB");
        $primaryOutlets = $readFromDB->getRepository(OutletOwnerSecondary::class)->findAll($lastSyncDate);
        foreach ($primaryOutlets as $outlet) {
            $updatingOutlets = new OutletPrimary();
            $updatingOutlets
                ->setCreatedAt($outlet->getCreatedAt())
                ->setModifiedAt($outlet->getModifiedAt())
                ->setName($outlet->getName());

            $owner = (new Owner())
                ->setCreatedAt(new DateTime())
                ->setModifiedAt(new DateTime())
                ->setName($outlet->getOwnerName());
            $updatingOutlets->setOwner($owner);
            $needUpdateDB->persist($updatingOutlets);
        }
        $this->logger->info("end updating Outlet {$needUpdateDB->getConnection()->getDatabase()} DB");

    }

    /**
     * @param EntityManager $readFromDB
     * @param EntityManager $needUpdateDB
     * @param DateTime|null $lastSyncDate
     * @return mixed|void
     * @throws ORMException
     */
    public function updateSecondaryDb(EntityManager $readFromDB, EntityManager $needUpdateDB, ?DateTime $lastSyncDate)
    {
        $this->logger->info("start updating Outlet {$needUpdateDB->getConnection()->getDatabase()} DB");
        $primaryOutlets = $readFromDB->getRepository(OutletPrimary::class)->findAll($lastSyncDate);
        foreach ($primaryOutlets as $outlet) {
            $updatingOutlets = new OutletOwnerSecondary();
            $updatingOutlets
                ->setName($outlet->getName())
                ->setCreatedAt($outlet->getCreatedAt())
                ->setModifiedAt($outlet->getModifiedAt())
                ->setOwnerName($outlet->getOwner()->getName());
            $needUpdateDB->persist($updatingOutlets);
        }
        $this->logger->info("end updating Outlet {$needUpdateDB->getConnection()->getDatabase()} DB");
    }
}