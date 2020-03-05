<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 02.03.2020 08:33
 */

namespace DBSynchronizer\Interfaces;


use DateTime;
use Doctrine\ORM\EntityManager;

interface UpdateInterface
{
    /**
     * @param EntityManager $readFromDB
     * @param EntityManager $needUpdateDB
     * @param DateTime|null $lastSyncDate
     * @return mixed
     */
    public function handle(EntityManager $readFromDB, EntityManager $needUpdateDB, ?DateTime $lastSyncDate);

    /**
     * @param EntityManager $readFromDB
     * @param EntityManager $needUpdateDB
     * @param DateTime|null $lastSyncDate
     * @return mixed
     */
    public function updatePrimaryDb(EntityManager $readFromDB, EntityManager $needUpdateDB, ?DateTime $lastSyncDate);

    /**
     * @param EntityManager $readFromDB
     * @param EntityManager $needUpdateDB
     * @param DateTime|null $lastSyncDate
     * @return mixed
     */
    public function updateSecondaryDb(EntityManager $readFromDB, EntityManager $needUpdateDB, ?DateTime $lastSyncDate);
}