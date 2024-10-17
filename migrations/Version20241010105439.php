<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241010105439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE out_of_service (id INT AUTO_INCREMENT NOT NULL, delivery_id INT NOT NULL, delivery_code VARCHAR(255) NOT NULL, delivery_date DATE NOT NULL, range1 VARCHAR(255) NOT NULL, range2 VARCHAR(255) NOT NULL, total_card INT NOT NULL, reason VARCHAR(255) NOT NULL, lock_date DATE NOT NULL, UNIQUE INDEX UNIQ_9F411DF312136921 (delivery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE out_of_service ADD CONSTRAINT FK_9F411DF312136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id)');
        $this->addSql('ALTER TABLE bin CHANGE range1 range1 VARCHAR(10) DEFAULT NULL, CHANGE range2 range2 VARCHAR(10) DEFAULT NULL, CHANGE last_emitted_pan last_emitted_pan VARCHAR(16) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE out_of_service DROP FOREIGN KEY FK_9F411DF312136921');
        $this->addSql('DROP TABLE out_of_service');
        $this->addSql('ALTER TABLE bin CHANGE range1 range1 VARCHAR(255) DEFAULT NULL, CHANGE range2 range2 VARCHAR(255) DEFAULT NULL, CHANGE last_emitted_pan last_emitted_pan VARCHAR(255) DEFAULT NULL');
    }
}
