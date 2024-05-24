<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240522225700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create user table';
    }

    public function up(Schema $schema): void
    {
        // Create user table
        $this->addSql('CREATE TABLE user (
            id INT AUTO_INCREMENT NOT NULL,
            username VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // Drop user table
        $this->addSql('DROP TABLE user');
    }
}
