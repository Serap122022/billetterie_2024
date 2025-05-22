<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402173523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE billets DROP panier_id');
        $this->addSql('ALTER TABLE panier DROP INDEX IDX_24CC0DF2A76ED395, ADD UNIQUE INDEX UNIQ_24CC0DF2A76ED395 (user_id)');
        $this->addSql('ALTER TABLE panier ADD billet_id INT NOT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF244973C78 FOREIGN KEY (billet_id) REFERENCES billets (id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF244973C78 ON panier (billet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE billets ADD panier_id INT NOT NULL');
        $this->addSql('ALTER TABLE panier DROP INDEX UNIQ_24CC0DF2A76ED395, ADD INDEX IDX_24CC0DF2A76ED395 (user_id)');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF244973C78');
        $this->addSql('DROP INDEX IDX_24CC0DF244973C78 ON panier');
        $this->addSql('ALTER TABLE panier DROP billet_id');
    }
}
