<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 05.03.2020 21:12
 */

namespace DBSynchronizer\Tests;


use DBSynchronizer\PrimaryDB\Entity\Employee;
use DBSynchronizer\SecondaryDB\Entity\Employee as EmployeeSecondary;
use DBSynchronizer\Service\RemoveExistence;
use DBSynchronizer\Tests\Fixtures\Primary\EmployeeFixture;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Exception;
use ReflectionClass;
use ReflectionException;

class RemoveExistenceTest extends AppTestCase
{
    /** @var EntityManager $primaryEM */
    protected $primaryEM;
    /** @var EntityManager $secondaryEM */
    protected $secondaryEM;
    /** @var RemoveExistence $handler */
    private $handler;

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
        $this->handler = new RemoveExistence($logger);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    public function testRemoveExistenceEmployee(): void
    {
        $employee1 = EmployeeFixture::build();
        $employee2 = EmployeeFixture::build();
        $this->primaryEM->persist($employee1);
        $this->primaryEM->persist($employee2);
        $this->primaryEM->flush();

        $primaryEmployee = $this->primaryEM->getRepository(Employee::class)->findAll();
        $this->assertCount(2, $primaryEmployee);


        $employeeSecondary1 = Fixtures\Secondary\EmployeeFixture::build();
        $employeeSecondary2 = Fixtures\Secondary\EmployeeFixture::build();
        $this->secondaryEM->persist($employeeSecondary1);
        $this->secondaryEM->persist($employeeSecondary2);
        $this->secondaryEM->flush();

        $secondaryEmployee = $this->secondaryEM->getRepository(EmployeeSecondary::class)->findAll();
        $this->assertCount(2, $secondaryEmployee);

        $this->invokeMethod($this->handler, 'employees', [$this->primaryEM, $this->secondaryEM]);

        $primaryEmployee = $this->primaryEM->getRepository(Employee::class)->findAll();
        $this->assertCount(1, $primaryEmployee);


        $secondaryEmployee = $this->secondaryEM->getRepository(EmployeeSecondary::class)->findAll();
        $this->assertCount(2, $secondaryEmployee);
    }

    /**
     * @param $object
     * @param $methodName
     * @param array $parameters
     * @return mixed
     * @throws ReflectionException
     */
    public function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}