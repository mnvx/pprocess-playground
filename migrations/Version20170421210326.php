<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170421210326 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable('account');
        $table->addColumn('id', 'integer', [
            'autoincrement' => true,
        ]);
        $table->addColumn('name', 'string');
        $table->setPrimaryKey(['id']);

        $table = $schema->createTable('account_import');
        $table->addColumn('id', 'integer');
        $table->addColumn('external_id', 'integer');
        $table->addIndex(['id']);
        $table->setPrimaryKey(['external_id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('account_import');
        $schema->dropTable('account');
    }
}
