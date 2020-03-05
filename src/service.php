<?php
/**
 * Daler B <dalerkbtut@gmail.com>
 * Date: 01.03.2020 13:43
 */

use DBSynchronizer\Actions\RemoveAction;
use DBSynchronizer\Actions\UpdateAction;
use DBSynchronizer\App\AppKernel as Application;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\EventDispatcher\EventDispatcher;


require_once '../vendor/autoload.php';

$app = new Application('DB-Synchronizer', '1.0-beta');

/** @var LoggerInterface $logger */
$logger = $app->getContainer()->get('logger');

$dispatcher = new EventDispatcher();
$dispatcher->addListener(ConsoleEvents::COMMAND, function (ConsoleCommandEvent $event) use ($logger) {
    $logger->info("run {$event->getInput()}");
});
$dispatcher->addListener(ConsoleEvents::ERROR, function (ConsoleErrorEvent $event) use ($logger) {
    $logger->error($event->getError()->getMessage());
    $logger->error($event->getError()->getTraceAsString());
});
$dispatcher->addListener(ConsoleEvents::TERMINATE, function (ConsoleTerminateEvent $event) use ($logger) {
    $logger->info('terminate ' . $event->getCommand()->getName());
});

$connection = $app->getContainer()->get('entity_manager_primary_db')->getConnection();

$helperSet = new HelperSet([
    'db' => new ConnectionHelper($connection),
    'dialog' => new QuestionHelper(),
]);
$app->setHelperSet($helperSet);
$app->setDispatcher($dispatcher);

$app->addCommands([
    new UpdateAction(),
    new RemoveAction()
]);

$app->run();