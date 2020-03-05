<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 05.03.2020 17:29
 */

namespace DBSynchronizer\Tests;



use Exception;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class ContainerTest extends AppTestCase
{
    public function setUp()
    {
        $this->initContainer();
    }

    /**
     * @throws Exception
     */
    public function testKernelContainer(): void
    {
        $this->assertInternalType('object', $this->container);

        /** @var Logger $logger */
        $logger = $this->container->get('logger');
        $this->assertInstanceOf(LoggerInterface::class, $logger);
        $this->assertCount(2, $logger->getHandlers());
    }

    /**
     * путь к БД1
     */
    public function testPrimaryDBPath(): void
    {
        $path = $this->container->getParameter('primary_db_path');
        $this->assertEquals('/var/www/.db/db1.db', $path);
    }
}