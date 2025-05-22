<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250428213325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ventes ADD type VARCHAR(50) NOT NULL, ADD stock INT NOT NULL, ADD vendus INT NOT NULL, ADD reste INT NOT NULL, DROP vendus_total, DROP reste_total');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ventes ADD vendus_total INT NOT NULL, ADD reste_total INT NOT NULL, DROP type, DROP stock, DROP vendus, DROP reste');
    }
}
