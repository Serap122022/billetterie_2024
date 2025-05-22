<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409124059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_A94BC0F0F85E0677 ON employes');
        $this->addSql('ALTER TABLE employes ADD nom VARCHAR(100) NOT NULL, ADD prenom VARCHAR(100) NOT NULL, ADD password VARCHAR(255) NOT NULL, ADD is_active TINYINT(1) NOT NULL, DROP username, DROP first_name, DROP password_hash, DROP active, CHANGE email email VARCHAR(180) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employes ADD first_name VARCHAR(255) NOT NULL, ADD password_hash VARCHAR(255) NOT NULL, ADD active INT NOT NULL, DROP nom, DROP prenom, DROP is_active, CHANGE email email VARCHAR(255) NOT NULL, CHANGE password username VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A94BC0F0F85E0677 ON employes (username)');
    }
}
