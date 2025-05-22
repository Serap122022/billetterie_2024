<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250224184757 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `admin` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, active TINYINT(1) NOT NULL, roles JSON NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, reset_token_expires_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_880E0D76F85E0677 (username), UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE billets (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(50) NOT NULL, tarif NUMERIC(6, 2) NOT NULL, custom_type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employes (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password_hash VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, active INT NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_A94BC0F0F85E0677 (username), UNIQUE INDEX UNIQ_A94BC0F0E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenements (id INT AUTO_INCREMENT NOT NULL, nom_evenement VARCHAR(255) NOT NULL, date_evenement DATE NOT NULL, description LONGTEXT NOT NULL, url_image VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `orders` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, order_key VARCHAR(255) NOT NULL, order_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, total_price NUMERIC(10, 2) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, postal_code VARCHAR(10) NOT NULL, city VARCHAR(100) NOT NULL, country VARCHAR(100) NOT NULL, INDEX IDX_E52FFDEEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders_item (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, user_id INT DEFAULT NULL, billet_id INT DEFAULT NULL, quantite INT NOT NULL, order_key VARCHAR(255) NOT NULL, unique_ticket_key VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B1CEE4B595154D4F (unique_ticket_key), INDEX IDX_B1CEE4B58D9F6D38 (order_id), INDEX IDX_B1CEE4B5A76ED395 (user_id), INDEX IDX_B1CEE4B544973C78 (billet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, billet_id INT NOT NULL, user_id INT NOT NULL, quantite INT NOT NULL, montant NUMERIC(10, 2) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_24CC0DF244973C78 (billet_id), INDEX IDX_24CC0DF2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, billet_id INT NOT NULL, utilisateur_id INT NOT NULL, montant NUMERIC(10, 2) NOT NULL, statut_paiement VARCHAR(255) NOT NULL, methode_paiement VARCHAR(255) NOT NULL, cle_paiement VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, UNIQUE INDEX UNIQ_6D28840D300C988C (cle_paiement), INDEX IDX_6D28840D44973C78 (billet_id), INDEX IDX_6D28840DFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE total_billets (id INT AUTO_INCREMENT NOT NULL, solo INT NOT NULL, duo INT NOT NULL, family INT NOT NULL, vendus INT NOT NULL, prix_total_recuperes DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, user_key VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, roles JSON NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, reset_token_expires_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), UNIQUE INDEX UNIQ_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `orders` ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders_item ADD CONSTRAINT FK_B1CEE4B58D9F6D38 FOREIGN KEY (order_id) REFERENCES `orders` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders_item ADD CONSTRAINT FK_B1CEE4B5A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders_item ADD CONSTRAINT FK_B1CEE4B544973C78 FOREIGN KEY (billet_id) REFERENCES billets (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF244973C78 FOREIGN KEY (billet_id) REFERENCES billets (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D44973C78 FOREIGN KEY (billet_id) REFERENCES billets (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `orders` DROP FOREIGN KEY FK_E52FFDEEA76ED395');
        $this->addSql('ALTER TABLE orders_item DROP FOREIGN KEY FK_B1CEE4B58D9F6D38');
        $this->addSql('ALTER TABLE orders_item DROP FOREIGN KEY FK_B1CEE4B5A76ED395');
        $this->addSql('ALTER TABLE orders_item DROP FOREIGN KEY FK_B1CEE4B544973C78');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF244973C78');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2A76ED395');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D44973C78');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DFB88E14F');
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('DROP TABLE billets');
        $this->addSql('DROP TABLE employes');
        $this->addSql('DROP TABLE evenements');
        $this->addSql('DROP TABLE `orders`');
        $this->addSql('DROP TABLE orders_item');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE total_billets');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
