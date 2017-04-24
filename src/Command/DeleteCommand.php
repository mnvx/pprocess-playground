<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @usage ./cli app:delete 1
 */
class DeleteCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:delete')
            ->setDescription('Delete account by external id')
            ->addArgument('externalId', InputArgument::REQUIRED, 'External account Id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Connection $connection */
        $connection = $qb = $this->getHelperSet()->get('db')->getConnection();
        $externalId = $input->getArgument('externalId');

        try {
            $connection->beginTransaction();

            $stmt = $connection->prepare("SELECT id FROM account_import WHERE external_id = :external_id");
            $stmt->execute([
                ':external_id' => $externalId,
            ]);
            $row = $stmt->fetch();

            if ($row) {
                $accountId = $row['id'];

                $stmt = $connection->prepare("DELETE FROM account_import WHERE external_id = :external_id");
                $stmt->execute([
                    ':external_id' => $externalId,
                ]);

                $stmt = $connection->prepare("DELETE FROM account WHERE id = :id");
                $stmt->execute([
                    ':id' => $accountId,
                ]);

                $output->writeln(sprintf('<info>Account %d deleted correctly</info>', $accountId));
            }
            else {
                $output->writeln(sprintf('<comment>Account with external id %d not found</comment>', $externalId));
            }

            $connection->commit();
        }
        catch (\Throwable $e) {
            $connection->rollBack();
            throw $e;
        }
    }

}