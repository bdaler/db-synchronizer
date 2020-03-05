<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 02.03.2020 08:45
 */

namespace DBSynchronizer\Service;


use DateTime;
use DBSynchronizer\Interfaces\UpdateInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;

abstract class BaseUpdater implements UpdateInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Updater constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param EntityManager $readFromDB
     * @param EntityManager $needUpdateDB
     * @param DateTime|null $lastSyncDate
     * @return mixed|void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function handle(EntityManager $readFromDB, EntityManager $needUpdateDB, ?DateTime $lastSyncDate)
    {
        if ($this->updatingSecondaryDB($needUpdateDB)) {
            $this->updateSecondaryDb($readFromDB, $needUpdateDB, $lastSyncDate);
        } else {
            $this->updatePrimaryDb($readFromDB, $needUpdateDB, $lastSyncDate);
        }
        $this->save($needUpdateDB);
    }

    /**
     * @param EntityManager $em
     * @return bool
     */
    public function updatingSecondaryDB(EntityManager $em): bool
    {
        return strpos($em->getConnection()->getDatabase(), 'db2.db') !== false;
    }

    /**
     * @param EntityManager $needUpdateDB
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function save(EntityManager $needUpdateDB): void
    {
        $needUpdateDB->flush();
    }
}