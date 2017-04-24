<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170423230038 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO users (id, name) VALUES 
            (1, 'user1'), 
            (2, 'user2'), 
            (3, 'user3'),
            (4, 'user4'),
            (5, 'user5'),
            (6, 'user6'),
            (7, 'user7'),
            (8, 'user8'),
            (9, 'user9'),
            (10, 'user10')
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("TRUNCATE TABLE users");
    }
}
