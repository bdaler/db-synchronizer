<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 05.03.2020 17:21
 */

namespace DBSynchronizer\Tests;


use DBSynchronizer\App\AppKernel;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;

abstract class AppTestCase extends TestCase
{
    /** @var Container $container */
    protected $container;
    /** @var EntityManager $primary */
    private $primary;
    /** @var EntityManager $secondary */
    private $secondary;

    public function setUp()
    {
        parent::setUp();

        $kernel = new AppKernel();
        $this->primary = $kernel->getContainer()->get('entity_manager_test_primary_db');
        $this->secondary = $kernel->getContainer()->get('entity_manager_test_secondary_db');
        $purger = new ORMPurger();
        $primaryExecutor = new ORMExecutor($this->primary, $purger);
        $primaryExecutor->purge();
        $secondaryExecutor = new ORMExecutor($this->secondary, $purger);
        $secondaryExecutor->purge();
    }
    protected function initContainer()
    {
        $kernel = new AppKernel();
        $this->container = $kernel->getContainer();
    }
}