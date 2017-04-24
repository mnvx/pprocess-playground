<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use malkusch\lock\mutex\FlockMutex;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @usage ./cli app:deadlock-two
 */
class DeadlockTwoCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:deadlock-two')
            ->setDescription('Illustration of deadlocks')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Connection $connection */
        $connection = $qb = $this->getHelperSet()->get('db')->getConnection();

        try {
//            $lockFile = dirname(dirname(__DIR__)) . '/temp/deadlock.lock';
//            $mutex = new FlockMutex(fopen($lockFile, 'r'));
//            $mutex->synchronized(function () use ($connection) {
            $connection->beginTransaction();

            $connection->prepare("UPDATE deadlock SET value = value + 1 WHERE id = 2")->execute();

            usleep(100000); // 0.1 sec

            $connection->prepare("UPDATE deadlock SET value = value + 1 WHERE id = 1")->execute();

            $connection->commit();
//            });
            $output->writeln('<info>Completed without deadlocks</info>');
        }
        catch (\Throwable $e) {
            $connection->rollBack();
            throw $e;
        }
    }

}