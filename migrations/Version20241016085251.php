<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241016085251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_range (id INT AUTO_INCREMENT NOT NULL, _order_id INT NOT NULL, _range_id INT NOT NULL, total INT NOT NULL, INDEX IDX_10192EB4A35F2858 (_order_id), INDEX IDX_10192EB444295D1 (_range_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_range ADD CONSTRAINT FK_10192EB4A35F2858 FOREIGN KEY (_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_range ADD CONSTRAINT FK_10192EB444295D1 FOREIGN KEY (_range_id) REFERENCES `range` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_range DROP FOREIGN KEY FK_10192EB4A35F2858');
        $this->addSql('ALTER TABLE order_range DROP FOREIGN KEY FK_10192EB444295D1');
        $this->addSql('DROP TABLE order_range');
    }
}
