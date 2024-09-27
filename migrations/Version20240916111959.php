<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240916111959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bank (id INT AUTO_INCREMENT NOT NULL, bank_code VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, contact VARCHAR(255) DEFAULT NULL, date DATE DEFAULT NULL, default_email VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, default_number VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bin (id INT AUTO_INCREMENT NOT NULL, bank_id INT NOT NULL, grant_date DATE DEFAULT NULL, serial VARCHAR(255) DEFAULT NULL, range1 VARCHAR(255) DEFAULT NULL, range2 VARCHAR(255) DEFAULT NULL, last_emitted_pan VARCHAR(255) DEFAULT NULL, INDEX IDX_AA275AED11C8FB41 (bank_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery (id INT AUTO_INCREMENT NOT NULL, delivery_code VARCHAR(255) DEFAULT NULL, date DATE DEFAULT NULL, range1 VARCHAR(255) DEFAULT NULL, range2 VARCHAR(255) DEFAULT NULL, card_total INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, order_code VARCHAR(255) DEFAULT NULL, order_date DATE DEFAULT NULL, date DATE DEFAULT NULL, serial VARCHAR(255) DEFAULT NULL, card_total INT DEFAULT NULL, range1 VARCHAR(255) DEFAULT NULL, range2 VARCHAR(255) DEFAULT NULL, card_type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bin ADD CONSTRAINT FK_AA275AED11C8FB41 FOREIGN KEY (bank_id) REFERENCES bank (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bin DROP FOREIGN KEY FK_AA275AED11C8FB41');
        $this->addSql('DROP TABLE bank');
        $this->addSql('DROP TABLE bin');
        $this->addSql('DROP TABLE delivery');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
