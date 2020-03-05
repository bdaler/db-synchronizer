<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 02.03.2020 23:00
 */

namespace DBSynchronizer\Service;


use DBSynchronizer\PrimaryDB\Entity\Employee as EmployeePrimary;
use DBSynchronizer\PrimaryDB\Entity\Outlet as OutletPrimary;
use DBSynchronizer\PrimaryDB\Entity\Sku as SkuPrimary;
use DBSynchronizer\SecondaryDB\Entity\Employee as EmployeeSecondary;
use DBSynchronizer\SecondaryDB\Entity\OutletOwner as OutletSecondary;
use DBSynchronizer\SecondaryDB\Entity\Sku as SkuSecondary;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;

/**
 * Class RemoveExistence
 * @package DBSynchronizer\Service
 */
class RemoveExistence
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * RemoveExistence constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param EntityManager $primaryDB
     * @param EntityManager $secondaryDB
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function calculateExistence(EntityManager $primaryDB, EntityManager $secondaryDB): void
    {
        $this->employees($primaryDB, $secondaryDB);
        $this->outlets($primaryDB, $secondaryDB);
        $this->sku($primaryDB, $secondaryDB);
    }

    /**
     * @param EntityManager $primaryDB
     * @param EntityManager $secondaryDB
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function employees(EntityManager $primaryDB, EntityManager $secondaryDB): void
    {
        $this->logger->info('Start calculating duplicate employees');
        $primaryEmployees = $primaryDB->getRepository(EmployeePrimary::class)->findAll();
        $secondaryEmployees = $secondaryDB->getRepository(EmployeeSecondary::class)->findAll();
        $max = max($primaryEmployees, $secondaryEmployees);

        $duplicateCount = 0;
        for ($i = 0; $i < count($max) - 1; $i++) {
            if (
                $this->arrayKeyExists($i, $primaryEmployees)
                && $this->arrayKeyExists($i, $secondaryEmployees)
                && $this->employeeEquals($primaryEmployees[$i], $secondaryEmployees[$i])
            ) {
                $this->logger->info('Find employee duplicate: ', (array)$primaryEmployees[$i]);
                $primaryDB->remove($primaryEmployees[$i]);
                $duplicateCount++;
            }
        }

        $this->save($primaryDB, $secondaryDB);
        $this->logger->info('Finish calculating duplicate employees. Amount of founded duplicate: ' . $duplicateCount);
    }

    /**
     * @param int $index
     * @param array $entity
     * @return bool
     */
    private function arrayKeyExists(int $index, array $entity): bool
    {
        return array_key_exists($index, $entity);
    }

    /**
     * @param EmployeePrimary $primaryEmployee
     * @param EmployeeSecondary $secondaryEmployee
     * @return bool
     */
    private function employeeEquals(EmployeePrimary $primaryEmployee, EmployeeSecondary $secondaryEmployee): bool
    {
        return $primaryEmployee->getName() && $secondaryEmployee->getName()
            && $primaryEmployee->getCreatedAt() && $secondaryEmployee->getCreatedAt()
            && $primaryEmployee->getModifiedAt() && $secondaryEmployee->getModifiedAt();
    }

    /**
     * @param EntityManager $primaryDB
     * @param EntityManager $secondaryDB
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function save(EntityManager $primaryDB, EntityManager $secondaryDB): void
    {
        $primaryDB->flush();
        $secondaryDB->flush();
    }

    /**
     * @param EntityManager $primaryDB
     * @param EntityManager $secondaryDB
     * @throws ORMException
     */
    private function outlets(EntityManager $primaryDB, EntityManager $secondaryDB): void
    {
        $this->logger->info('Start calculating duplicate outlets');
        $primaryRepository = $primaryDB->getRepository(OutletPrimary::class);
        $primaryOutlets = $primaryRepository->findAll();

        $secondaryRepository = $secondaryDB->getRepository(OutletSecondary::class);
        $secondaryOutlets = $secondaryRepository->findAll();

        $duplicateCount = 0;
        for ($i = 0; $i < count(max($primaryOutlets, $secondaryOutlets)) - 1; $i++) {
            if (
                $this->arrayKeyExists($i, $primaryOutlets)
                && $this->arrayKeyExists($i, $secondaryOutlets)
                && $this->outletsEqual($primaryOutlets[$i], $secondaryOutlets[$i])
            ) {
                $this->logger->info('Find outlet duplicate: ', (array)$primaryOutlets[$i]);
                $primaryDB->remove($primaryOutlets[$i]);
                $duplicateCount++;
            }
        }

        $this->save($primaryDB, $secondaryDB);
        $this->logger->info('Finish calculating duplicate outlet. Amount of founded duplicate: ' . $duplicateCount);
    }

    /**
     * @param OutletPrimary $primaryOutlet
     * @param OutletSecondary $secondaryOutlet
     * @return bool
     */
    private function outletsEqual(OutletPrimary $primaryOutlet, OutletSecondary $secondaryOutlet): bool
    {
        return $primaryOutlet->getModifiedAt() && $secondaryOutlet->getModifiedAt()
            && $primaryOutlet->getCreatedAt() && $secondaryOutlet->getCreatedAt()
            && $primaryOutlet->getName() && $secondaryOutlet->getName()
            && $primaryOutlet->getOwner()->getName() && $secondaryOutlet->getOwnerName();
    }

    /**
     * @param EntityManager $primaryDB
     * @param EntityManager $secondaryDB
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function sku(EntityManager $primaryDB, EntityManager $secondaryDB): void
    {
        $this->logger->info('Start calculating duplicate sku');
        $primaryRepository = $primaryDB->getRepository(SkuPrimary::class);
        $primarySku = $primaryRepository->findAll();

        $secondaryRepository = $secondaryDB->getRepository(SkuSecondary::class);
        $secondarySku = $secondaryRepository->findAll();

        $duplicateCount = 0;
        for ($i = 0; $i < count(max($primarySku, $secondarySku)) - 1; $i++) {
            if (
                $this->arrayKeyExists($i, $primarySku)
                && $this->arrayKeyExists($i, $secondarySku)
                && $this->skuEqual($primarySku[$i], $secondarySku[$i])
            ) {
                $this->logger->info('find sku duplicate: ', (array)$primarySku[$i]);
                $primaryDB->remove($primarySku[$i]);
                $duplicateCount++;
            }
        }

        $this->save($primaryDB, $secondaryDB);
        $this->logger->info('Finish calculating duplicate outlet.  Amount of founded duplicate: ' . $duplicateCount);
    }

    /**
     * @param SkuPrimary $primarySku
     * @param SkuSecondary $secondarySku
     * @return bool
     */
    private function skuEqual(SkuPrimary $primarySku, SkuSecondary $secondarySku): bool
    {
        return $primarySku->getModifiedAt() && $secondarySku->getModifiedAt()
            && $primarySku->getCreatedAt() && $secondarySku->getCreatedAt()
            && $primarySku->getName() && $secondarySku->getName();
    }
}