<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250406165952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_A94BC0F0F85E0677 ON employes');
        $this->addSql('ALTER TABLE employes ADD is_active TINYINT(1) NOT NULL, DROP password_hash, DROP created_at, DROP active, DROP roles');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employes ADD password_hash VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL, ADD active INT NOT NULL, ADD roles JSON NOT NULL, DROP is_active');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A94BC0F0F85E0677 ON employes (username)');
    }
}
