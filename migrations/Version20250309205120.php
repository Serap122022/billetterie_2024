<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250309205120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ventes (id INT AUTO_INCREMENT NOT NULL, billet_id INT DEFAULT NULL, stock INT NOT NULL, vendus INT NOT NULL, reste INT NOT NULL, prix_recupere NUMERIC(10, 2) NOT NULL, INDEX IDX_64EC489A44973C78 (billet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ventes ADD CONSTRAINT FK_64EC489A44973C78 FOREIGN KEY (billet_id) REFERENCES billets (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ventes DROP FOREIGN KEY FK_64EC489A44973C78');
        $this->addSql('DROP TABLE ventes');
    }
}
