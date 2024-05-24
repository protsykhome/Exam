<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240522193700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Create company table
        $this->addSql('CREATE TABLE company (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL,
            address VARCHAR(255) NOT NULL,
            website VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create employee table
        $this->addSql('CREATE TABLE employee (
            id INT AUTO_INCREMENT NOT NULL,
            first_name VARCHAR(255) NOT NULL,
            last_name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            company_id INT NOT NULL,
            PRIMARY KEY(id),
            CONSTRAINT FK_employee_company FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Create project table
        $this->addSql('CREATE TABLE project (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(255) NOT NULL,
            description VARCHAR(255) NOT NULL,
            company_id INT NOT NULL,
            PRIMARY KEY(id),
            CONSTRAINT FK_project_company FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // Drop project table
        $this->addSql('DROP TABLE project');

        // Drop employee table
        $this->addSql('DROP TABLE employee');

        // Drop company table
        $this->addSql('DROP TABLE company');
    }
}
