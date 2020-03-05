<?php
/**
 * author: BDaler (dalerkbtut@gmail.com)
 * @date: 02.03.2020 22:56
 */

namespace DBSynchronizer\Actions;


use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RemoveAction extends Command
{
    protected function configure()
    {
        $this
            ->setName('run:remove:existence')
            ->setDescription('remove duplicates from DBs');
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

        $primaryDB = $container->get('entity_manager_primary_db');
        $secondaryDB = $container->get('entity_manager_secondary_db');

        $container->get('remove_existence')->calculateExistence($primaryDB, $secondaryDB);
    }
}