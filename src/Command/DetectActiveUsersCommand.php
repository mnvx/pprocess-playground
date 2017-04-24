<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use malkusch\lock\mutex\FlockMutex;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @usage ./cli app:detect-active-users
 */
class DetectActiveUsersCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:detect-active-users')
            ->setDescription('Illustration of possible problem with table refreshing')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Connection $connection */
        $connection = $qb = $this->getHelperSet()->get('db')->getConnection();

        try {
            $connection->beginTransaction();

            //$connection->prepare("SELECT id FROM users WHERE id IN (3, 5, 6, 10) FOR UPDATE")->execute();
            $connection->prepare("SELECT id FROM users_active FOR UPDATE")->execute();
            $connection->prepare("DELETE FROM users_active")->execute();
            //$connection->prepare("TRUNCATE TABLE users_active")->execute();

            usleep(100000); // 0.1 sec

            $connection->prepare("INSERT INTO users_active (id) VALUES (3), (5), (6), (10)")->execute();

            $connection->commit();
            $output->writeln('<info>users_active refreshed</info>');
        }
        catch (\Throwable $e) {
            $connection->rollBack();
            throw $e;
        }
    }

}