<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @usage echo -e '{"id":1,"name":"record1"}' | ./cli app:import
 */
class ImportCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:import')
            ->setDescription('Illustration of possible problem with import')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $jsonInput = stream_get_line(STDIN, null);
        $data = json_decode($jsonInput, true);

        /** @var Connection $connection */
        $connection = $qb = $this->getHelperSet()->get('db')->getConnection();

        try {
            $connection->beginTransaction();

            $stmt = $connection->prepare("SELECT id FROM account_import WHERE external_id = :external_id");
            $stmt->execute([
                ':external_id' => $data['id'],
            ]);
            $row = $stmt->fetch();

            usleep(100000); // 0.1 sec

            if ($row) {
                $stmt = $connection->prepare("UPDATE account SET name = :name WHERE id = (
                    SELECT id FROM account_import WHERE external_id = :external_id
                )");
                $stmt->execute([
                    ':name' => $data['name'],
                    ':external_id' => $data['id'],
                ]);
                $accountId = $row['id'];
            }
            else {
                $stmt = $connection->prepare("INSERT INTO account (name) VALUES (:name)");
                $stmt->execute([
                    ':name' => $data['name'],
                ]);
                $accountId = $connection->lastInsertId();

                $stmt = $connection->prepare("INSERT INTO account_import (id, external_id) VALUES (:id, :external_id)");
                $stmt->execute([
                    ':id' => $accountId,
                    ':external_id' => $data['id'],
                ]);
            }

            $connection->commit();
            $output->writeln(sprintf('<info>Account %d imported correctly</info>', $accountId));
        }
        catch (\Throwable $e) {
            $connection->rollBack();
            throw $e;
        }
    }

}