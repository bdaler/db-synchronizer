<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 05.03.2020 20:52
 */

namespace DBSynchronizer\Tests;


use DBSynchronizer\Interfaces\UpdateInterface;
use DBSynchronizer\PrimaryDB\Entity\Outlet;
use DBSynchronizer\SecondaryDB\Entity\OutletOwner;
use DBSynchronizer\Service\UpdateOutlet;
use DBSynchronizer\Tests\Fixtures\Primary\OutletFixture;
use DBSynchronizer\Tests\Fixtures\Secondary\OutletOwnerFixture;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;

class UpdateOutletTest extends AppTestCase
{
    /** @var UpdateInterface $handler */
    private $handler;
    /** @var EntityManager $primaryEM */
    protected $primaryEM;
    /** @var EntityManager $secondaryEM */
    protected $secondaryEM;

    /**
     * @throws \Exception
     */
    public function setUp()
    {
        parent::setUp();
        $this->initContainer();
        $this->primaryEM = $this->container->get('entity_manager_test_primary_db');
        $this->secondaryEM = $this->container->get('entity_manager_test_secondary_db');

        $logger = $this->container->get('logger');
        $this->handler = new UpdateOutlet($logger);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function testUpdateSecondaryDb(): void
    {
        $primaryOutlet = OutletFixture::build();
        $this->primaryEM->persist($primaryOutlet);
        $this->primaryEM->flush();

        $this->handler->updateSecondaryDb($this->primaryEM, $this->secondaryEM, null);
        $this->secondaryEM->flush();

        $secondary = $this->secondaryEM->getRepository(OutletOwner::class)->findAll();

        $this->assertCount(1, $secondary);
        $this->assertEquals(current($secondary)->getName(), $primaryOutlet->getName());
        $this->assertEquals(current($secondary)->getCreatedAt(), $primaryOutlet->getCreatedAt());
        $this->assertEquals(current($secondary)->getModifiedAt(), $primaryOutlet->getModifiedAt());
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function testUpdatePrimaryDb(): void
    {
        $secondaryOutlet = OutletOwnerFixture::build();
        $this->secondaryEM->persist($secondaryOutlet);
        $this->secondaryEM->flush();

        $this->handler->updatePrimaryDb($this->secondaryEM, $this->primaryEM, null);
        $this->primaryEM->flush();

        $primaryEmployee = $this->primaryEM->getRepository(Outlet::class)->findAll();

        $this->assertCount(1, $primaryEmployee);
        $this->assertEquals(current($primaryEmployee)->getName(), $secondaryOutlet->getName());
        $this->assertEquals(current($primaryEmployee)->getCreatedAt(), $secondaryOutlet->getCreatedAt());
        $this->assertEquals(current($primaryEmployee)->getModifiedAt(), $secondaryOutlet->getModifiedAt());
    }
}