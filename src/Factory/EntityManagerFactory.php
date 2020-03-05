<?php


namespace DBSynchronizer\Factory;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;

/**
 * Class EntityManagerFactory
 *
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 01.03.2020 13:27
 * @package DBSynchronizer\src\Factory
 */
class EntityManagerFactory
{
    public const PRIMARY_DB = 1;
    public const SECONDARY_DB = 2;

    /**
     * @param string $driver
     * @param string $path
     * @param int $dbType
     * @param bool $isDebug
     * @return EntityManager
     * @throws ORMException
     */
    public function build(string $driver, string $path, int $dbType, bool $isDebug = false): EntityManager
    {
        $connectionParams = [
            'driver' => $driver,
            'path' => $path,
        ];

        $namespaces = ['/var/www/src/PrimaryDB/Resource/mapping' => 'DBSynchronizer\PrimaryDB\Entity'];
        if ($dbType === self::SECONDARY_DB) {
            $namespaces = ['/var/www/src/SecondaryDB/Resource/mapping' => 'DBSynchronizer\SecondaryDB\Entity'];
        }
        $driver = new SimplifiedYamlDriver($namespaces);
        $config = Setup::createConfiguration($isDebug);
        $config->setMetadataDriverImpl($driver);

        return EntityManager::create($connectionParams, $config);
    }
}