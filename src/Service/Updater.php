<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 01.03.2020 17:29
 */

namespace DBSynchronizer\Service;


use DateTime;
use DBSynchronizer\Enum\LastSyncStatuses;
use DBSynchronizer\PrimaryDB\Entity\LastSync as LastSyncPrimary;
use DBSynchronizer\SecondaryDB\Entity\LastSync as LastSyncSecondary;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Updater
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Updater constructor.
     * @param ContainerInterface $container
     * @param LoggerInterface $logger
     */
    public function __construct(ContainerInterface $container, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->container = $container;
    }

    /**
     * @param EntityManager $readFromDB
     * @param EntityManager $needUpdateDB
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function handle(EntityManager $readFromDB, EntityManager $needUpdateDB): void
    {
        $lastSuccessSynced = $this->getLastSyncDate($readFromDB, $needUpdateDB);
        $lastSyncDate = null;
        if ($lastSuccessSynced !== null) {
            $lastSyncDate = $lastSuccessSynced->getDateTime();
        }
        try {
            $this->container->get('employee_updater')
                ->handle($readFromDB, $needUpdateDB, $lastSyncDate);

            $this->container->get('outlet_updater')
                ->handle($readFromDB, $needUpdateDB, $lastSyncDate);

            $this->container->get('sku_updater')
                ->handle($readFromDB, $needUpdateDB, $lastSyncDate);

            $this->updateLastSyncDate($readFromDB, $needUpdateDB, true);
        } catch (ORMException $exception) {
            $this->logger->critical('something went wrong', $exception->getTrace());
            $this->updateLastSyncDate($readFromDB, $needUpdateDB, false);
        }

        $this->save($readFromDB, $needUpdateDB);
        $this->logger->info("database: {$needUpdateDB->getConnection()->getDatabase()}  updated from: {$readFromDB->getConnection()->getDatabase()}");
    }

    /**
     * @param EntityManager $readFromDB
     * @param EntityManager $needUpdateDB
     * @return mixed|null
     * @throws NonUniqueResultException
     */
    private function getLastSyncDate(EntityManager $readFromDB, EntityManager $needUpdateDB)
    {
        /**
         * если обновляем вторую БД
         * берем дату последней обновлений из второй БД
         */
        $lastSuccessSynced = null;
        if (strpos($readFromDB->getConnection()->getDatabase(), 'db2.db') !== false) {
            $lastSuccessSynced = $readFromDB->getRepository(LastSyncSecondary::class)->lastSuccessSynced();
        } else {
            $lastSuccessSynced = $needUpdateDB->getRepository(LastSyncSecondary::class)->lastSuccessSynced();
        }
        return $lastSuccessSynced;
    }

    /**
     * @param EntityManager $readFromDB
     * @param EntityManager $needUpdateDB
     * @param bool $isSuccess
     * @throws ORMException
     */
    private function updateLastSyncDate(EntityManager $readFromDB, EntityManager $needUpdateDB, bool $isSuccess): void
    {
        /**
         * если обновляем первую БД
         * соответственно обновляем LastSync в первой БД
         */
        $now = new DateTime();
        $status = $isSuccess ? LastSyncStatuses::SUCCESSES : LastSyncStatuses::FAILED;
        if (strpos($needUpdateDB->getConnection()->getDatabase(), 'db2.db') !== false) {
            $lastSync = new LastSyncSecondary();
            $lastSync
                ->setDateTime($now)
                ->setStatus($status);
            $needUpdateDB->persist($lastSync);
        } else {
            $lastSync = new LastSyncPrimary();
            $lastSync
                ->setDateTime($now)
                ->setStatus($status);
            $needUpdateDB->persist($lastSync);
        }
    }

    /**
     * @param EntityManager $primaryEm
     * @param EntityManager $secondaryEm
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function save(EntityManager $primaryEm, EntityManager $secondaryEm): void
    {
        $primaryEm->flush();
        $secondaryEm->flush();
    }
}