<?php


namespace DBSynchronizer\Actions;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class UpdateAction
 * @package DBSynchronizer\Actions
 */
class UpdateAction extends Command
{
    /**
     * Первый БД
     */
    public const PRIMARY_DB = 'primary';
    /**
     * Второй БД
     */
    public const SECONDARY_DB = 'secondary';

    /**
     * Настройки
     */
    protected function configure()
    {
        $this
            ->setName('run:update')
            ->setDescription('update db')
            ->addOption('fromDB', 'fd', InputOption::VALUE_REQUIRED, 'updated db from...')
            ->addOption('toDB', 'td', InputOption::VALUE_REQUIRED, 'update this db');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws ORMException
     * @throws OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ContainerInterface $container */
        $container = $this->getApplication()->getContainer();
        /** @var LoggerInterface $logger */
        $logger = $container->get('logger');

        $fromDb = $input->getOption('fromDB');
        $toDb = $input->getOption('toDB');

        $this->validate($fromDb, $toDb, $logger, $output);

        $primaryEm = $this->getEntityManager($fromDb, $container);
        $secondaryEm = $this->getEntityManager($toDb, $container);

        $container->get('updater')->handle($primaryEm, $secondaryEm);
    }

    /**
     * @param string $from
     * @param string $to
     * @param LoggerInterface $logger
     * @param OutputInterface $output
     */
    private function validate(string $from, string $to, LoggerInterface $logger, OutputInterface $output): void
    {
        $availableDB = [self::PRIMARY_DB, self::SECONDARY_DB];
        if (!in_array($from, $availableDB, true) || !in_array($to, $availableDB, true)) {
            $logger->error('invalid arguments, arguments must be primary or secondary');
            $output->write('invalid arguments: at this time i can work only with database name primary and secondary :(');
            exit();
        }
    }

    /**
     * @param string $dbName
     * @param ContainerInterface $container
     * @return EntityManager|object|null
     */
    private function getEntityManager(string $dbName, ContainerInterface $container)
    {
        if ($dbName === self::PRIMARY_DB) {
            return $container->get('entity_manager_primary_db');
        }

        if ($dbName === self::SECONDARY_DB) {
            return $container->get('entity_manager_secondary_db');
        }
    }
}