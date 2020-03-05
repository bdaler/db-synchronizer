<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 04.03.2020 20:44
 */

namespace DBSynchronizer\Service;


use DBSynchronizer\PrimaryDB\Entity\Employee as EmployeePrimary;
use DBSynchronizer\PrimaryDB\Entity\Outlet as OutletPrimary;
use DBSynchronizer\PrimaryDB\Entity\Owner;
use DBSynchronizer\PrimaryDB\Entity\Owner as OwnerPrimary;
use DBSynchronizer\PrimaryDB\Entity\Sku as SkuPrimary;
use DBSynchronizer\SecondaryDB\Entity\Employee as EmployeeSecondary;
use DBSynchronizer\SecondaryDB\Entity\OutletOwner as OutletSecondary;
use DBSynchronizer\SecondaryDB\Entity\Sku as SkuSecondary;
use DBSynchronizer\SecondaryDB\Entity\SkuStock;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;

class EntityListener
{
    /**
     * @var EntityManager
     */
    private $primary;
    /**
     * @var EntityManager
     */
    private $secondary;

    /**
     * EntityListener constructor.
     * @param EntityManager $primary
     * @param EntityManager $secondary
     */
    public function __construct(EntityManager $primary, EntityManager $secondary)
    {
        $this->primary = $primary;
        $this->secondary = $secondary;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     * @throws Exception
     */
    public function postUpdate(LifecycleEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getEntity();
        $em = $eventArgs->getEntityManager();
        switch (true) {
            case $entity instanceof EmployeePrimary:
                $this->updateSecondaryEmployee($entity);
                break;
            case $entity instanceof EmployeeSecondary:
                $this->updatePrimaryEmployee($entity);
                break;
            case $entity instanceof OutletPrimary:
                $this->updateSecondaryOutlet($entity);
                break;
            case $entity instanceof OutletSecondary:
                $this->updatePrimaryOutlet($entity);
                break;
            case $entity instanceof OwnerPrimary:
                $this->updateSecondaryOwner($entity);
                break;
            case $entity instanceof SkuSecondary:
                $this->updatePrimarySkuName($entity);
                break;
            case $entity instanceof SkuPrimary:
                $this->updateSecondarySkuAndSkuStock($entity);
                break;
            case $entity instanceof SkuStock:
                $this->updatePrimarySku($entity);
                break;
            default:
                throw new Exception('Unknown entity');
        }
    }

    /**
     * @param EmployeePrimary $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function updateSecondaryEmployee(EmployeePrimary $entity): void
    {
        $repository = $this->secondary->getRepository(EmployeeSecondary::class);

        $employee = $repository->findOneBy(['name', $entity->getName()]);
        if ($employee instanceof EmployeeSecondary) {
            $employee->setName($entity->getName());
            $this->secondary->persist($employee);
            $this->secondary->flush();
        }
    }

    /**
     * @param EmployeeSecondary $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function updatePrimaryEmployee(EmployeeSecondary $entity): void
    {
        $repo = $this->primary->getRepository(EmployeePrimary::class);
        $employee = $repo->findOneBy(['name' => $entity->getName()]);

        if ($employee instanceof EmployeeSecondary) {
            $employee->setName($entity->getName());
            $this->primary->persist($employee);
            $this->primary->flush();
        }
    }

    /**
     * @param OutletPrimary $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function updateSecondaryOutlet(OutletPrimary $entity): void
    {
        $repo = $this->secondary->getRepository(OutletSecondary::class);
        $outlet = $repo->findOneBy(['name' => $entity->getName()]);

        if ($outlet instanceof OutletSecondary) {
            $outlet
                ->setName($entity->getName())
                ->setOwnerName($entity->getOwner()->getName());
            $this->secondary->persist($outlet);
            $this->secondary->flush();
        }
    }

    /**
     * @param OutletSecondary $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function updatePrimaryOutlet(OutletSecondary $entity): void
    {
        $repo = $this->primary->getRepository(OutletPrimary::class);
        $outlet = $repo->findOneBy(['name' => $entity->getName()]);

        if ($outlet instanceof OutletPrimary) {
            $owner = new Owner();
            $owner->setName($entity->getOwnerName());
            $outlet->setOwner($owner);
            $this->primary->persist($outlet);
        }

        $primaryOwner = $this->primary->getRepository(OwnerPrimary::class)
            ->findOneBy(['name' => $entity->getOwnerName()]);
        if ($primaryOwner instanceof OwnerPrimary) {
            $primaryOwner->setName($entity->getOwnerName());
            $this->primary->persist($primaryOwner);
        }

        $this->primary->flush();
    }

    /**
     * @param OwnerPrimary $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function updateSecondaryOwner(OwnerPrimary $entity): void
    {
        $repo = $this->secondary->getRepository(OutletSecondary::class);
        $outlet = $repo->findOneBy(['ownerName' => $entity->getName()]);

        if ($outlet instanceof OutletSecondary) {
            $outlet->setOwnerName($entity->getName());
            $this->secondary->persist($outlet);
            $this->secondary->flush();
        }
    }

    /**
     * @param SkuSecondary $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function updatePrimarySkuName(SkuSecondary $entity): void
    {
        $repo = $this->primary->getRepository(SkuPrimary::class);
        $sku = $repo->findOneBy(['name' => $entity->getName()]);

        if ($sku instanceof SkuPrimary) {
            $sku->setName($entity->getName());
            $this->primary->persist($sku);
            $this->primary->flush();
        }
    }

    /**
     * @param SkuPrimary $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function updateSecondarySkuAndSkuStock(SkuPrimary $entity): void
    {
        $repo = $this->secondary->getRepository(SkuSecondary::class);
        $sku = $repo->findOneBy(['name' => $entity->getName()]);

        if ($sku instanceof SkuSecondary) {
            $this->secondary->persist($sku);
        }

        $skuStock = $this->secondary->getRepository(SkuStock::class)
            ->findOneBy(['stock' => $entity->getStock()]);
        if ($skuStock instanceof SkuStock) {
            $this->secondary->persist($skuStock);
        }
        $this->secondary->flush();
    }

    /**
     * @param SkuStock $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function updatePrimarySku(SkuStock $entity): void
    {
        $repo = $this->primary->getRepository(SkuPrimary::class);
        $sku = $repo->findOneBy(['stock' => $entity->getStock()]);

        if ($sku instanceof SkuPrimary) {
            $sku->setStock($entity->getStock());
            $this->primary->persist();
            $this->primary->flush();
        }
    }
}