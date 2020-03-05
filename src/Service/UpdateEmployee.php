<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 02.03.2020 08:45
 */

namespace DBSynchronizer\Service;


use DateTime;
use DBSynchronizer\PrimaryDB\Entity\Employee as EmployeePrimary;
use DBSynchronizer\SecondaryDB\Entity\Employee as EmployeeSecondary;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;

/**
 * Class UpdateEmployee
 * @package DBSynchronizer\Service
 */
class UpdateEmployee extends BaseUpdater
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
        $this->logger->info("start updating Employee {$needUpdateDB->getConnection()->getDatabase()} DB");
        $employees = $readFromDB->getRepository(EmployeeSecondary::class)->findAll($lastSyncDate);
        foreach ($employees as $employee) {
            $newEmployee = new EmployeePrimary();
            $newEmployee
                ->setName($employee->getName())
                ->setCreatedAt($employee->getCreatedAt())
                ->setModifiedAt($employee->getModifiedAt());
            $needUpdateDB->persist($newEmployee);
        }
        $this->logger->info("end updating Employee {$needUpdateDB->getConnection()->getDatabase()} DB");
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
        $this->logger->info("start updating Employee {$needUpdateDB->getConnection()->getDatabase()} DB");
        $employees = $readFromDB->getRepository(EmployeePrimary::class)->findAll($lastSyncDate);
        foreach ($employees as $employee) {
            $newEmployee = new EmployeeSecondary();
            $newEmployee
                ->setName($employee->getName())
                ->setCreatedAt($employee->getCreatedAt())
                ->setModifiedAt($employee->getModifiedAt());
            $needUpdateDB->persist($newEmployee);
        }
        $this->logger->info("end updating Employee {$needUpdateDB->getConnection()->getDatabase()} DB");
    }
}