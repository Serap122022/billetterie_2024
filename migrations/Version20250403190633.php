<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403190633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier_billets DROP FOREIGN KEY FK_B5755FE9B9EBD317');
        $this->addSql('ALTER TABLE panier_billets DROP FOREIGN KEY FK_B5755FE9F77D927C');
        $this->addSql('DROP TABLE panier_billets');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier_billets (panier_id INT NOT NULL, billets_id INT NOT NULL, INDEX IDX_B5755FE9F77D927C (panier_id), INDEX IDX_B5755FE9B9EBD317 (billets_id), PRIMARY KEY(panier_id, billets_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE panier_billets ADD CONSTRAINT FK_B5755FE9B9EBD317 FOREIGN KEY (billets_id) REFERENCES billets (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_billets ADD CONSTRAINT FK_B5755FE9F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
