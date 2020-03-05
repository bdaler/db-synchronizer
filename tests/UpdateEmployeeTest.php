<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 05.03.2020 17:42
 */

namespace DBSynchronizer\Tests;


use DBSynchronizer\Interfaces\UpdateInterface;
use DBSynchronizer\PrimaryDB\Entity\Employee as PrimaryEmployee;
use DBSynchronizer\SecondaryDB\Entity\Employee;
use DBSynchronizer\Service\UpdateEmployee;
use DBSynchronizer\Tests\fixtures\Primary\EmployeeFixture;
use DBSynchronizer\Tests\fixtures\Secondary\EmployeeFixture as SecondaryEmployeeFixture;
use DBSynchronizer\Tests\Fixtures\Secondary\LastSyncDateFixture;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;

/**
 * Class UpdateEmployeeTest
 * @package DBSynchronizer\Tests
 */
class UpdateEmployeeTest extends AppTestCase
{
    /** @var UpdateInterface $handler */
    private $handler;
    /** @var EntityManager $primaryEM */
    protected $primaryEM;
    /** @var EntityManager $secondaryEM */
    protected $secondaryEM;

    /**
     * @throws Exception
     */
    public function setUp()
    {
        parent::setUp();
        $this->initContainer();
        $this->primaryEM = $this->container->get('entity_manager_test_primary_db');
        $this->secondaryEM = $this->container->get('entity_manager_test_secondary_db');
        
        $logger = $this->container->get('logger');
        $this->handler = new UpdateEmployee($logger);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function testUpdateSecondaryDb(): void
    {
        $primaryEmployee = EmployeeFixture::build();
        $this->primaryEM->persist($primaryEmployee);
        $this->primaryEM->flush();

        $this->handler->updateSecondaryDb($this->primaryEM, $this->secondaryEM, null);
        $this->secondaryEM->flush();
        $secondary = $this->secondaryEM->getRepository(Employee::class)->findAll();

        $this->assertCount(1, $secondary);
        $this->assertEquals(current($secondary)->getName(), $primaryEmployee->getName());
        $this->assertEquals(current($secondary)->getCreatedAt(), $primaryEmployee->getCreatedAt());
        $this->assertEquals(current($secondary)->getModifiedAt(), $primaryEmployee->getModifiedAt());
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function testUpdatePrimaryDb(): void
    {
        $secondaryEmployee = SecondaryEmployeeFixture::build();
        $this->secondaryEM->persist($secondaryEmployee);
        $this->secondaryEM->flush();

        $this->handler->updatePrimaryDb($this->secondaryEM, $this->primaryEM, null);
        $this->primaryEM->flush();
        $primaryEmployee = $this->primaryEM->getRepository(PrimaryEmployee::class)->findAll();

        $this->assertCount(1, $primaryEmployee);
        $this->assertEquals(current($primaryEmployee)->getName(), $secondaryEmployee->getName());
        $this->assertEquals(current($primaryEmployee)->getCreatedAt(), $secondaryEmployee->getCreatedAt());
        $this->assertEquals(current($primaryEmployee)->getModifiedAt(), $secondaryEmployee->getModifiedAt());
    }

    /**
     * Берем записей поле createdAt которых больше чем lastSyncDate
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function testUpdatePrimaryDbWithLastSyncDate(): void
    {
        $secondaryEmployee1 = SecondaryEmployeeFixture::build();
        $secondaryEmployee2 = SecondaryEmployeeFixture::build();
        $secondaryEmployee2->setCreatedAt(new \DateTime('+ 2 hour'));
        $this->secondaryEM->persist($secondaryEmployee1);
        $this->secondaryEM->persist($secondaryEmployee2);

        $lastSyncDate = LastSyncDateFixture::build();
        $this->secondaryEM->persist($lastSyncDate);
        $this->secondaryEM->flush();

        $this->handler->updatePrimaryDb($this->secondaryEM, $this->primaryEM, $lastSyncDate->getDateTime());
        $this->primaryEM->flush();
        $primaryEmployee = $this->primaryEM->getRepository(PrimaryEmployee::class)->findAll();

        $this->assertCount(1, $primaryEmployee);
        $this->assertEquals(current($primaryEmployee)->getName(), $secondaryEmployee2->getName());
        $this->assertEquals(current($primaryEmployee)->getCreatedAt(), $secondaryEmployee2->getCreatedAt());
        $this->assertEquals(current($primaryEmployee)->getModifiedAt(), $secondaryEmployee2->getModifiedAt());
    }

    /**
     * Берем записей поле createdAt которых больше чем lastSyncDate
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function testUpdateSecondaryDbWithLastSyncDate(): void
    {
        $primaryEmployee1 = EmployeeFixture::build();
        $primaryEmployee2 = EmployeeFixture::build();
        $primaryEmployee2->setCreatedAt(new \DateTime('+ 2 hour'));
        $this->primaryEM->persist($primaryEmployee1);
        $this->primaryEM->persist($primaryEmployee2);

        $lastSyncDate = Fixtures\Primary\LastSyncDateFixture::build();
        $this->primaryEM->persist($lastSyncDate);
        $this->primaryEM->flush();

        $this->handler->updateSecondaryDb($this->primaryEM, $this->secondaryEM, $lastSyncDate->getDateTime());
        $this->secondaryEM->flush();
        $secondaryEmployee = $this->secondaryEM->getRepository(Employee::class)->findAll();

        $this->assertCount(1, $secondaryEmployee);
        $this->assertEquals(current($secondaryEmployee)->getName(), $primaryEmployee2->getName());
        $this->assertEquals(current($secondaryEmployee)->getCreatedAt(), $primaryEmployee2->getCreatedAt());
        $this->assertEquals(current($secondaryEmployee)->getModifiedAt(), $primaryEmployee2->getModifiedAt());
    }
}