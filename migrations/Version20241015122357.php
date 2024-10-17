<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241015122357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `range` (id INT AUTO_INCREMENT NOT NULL, bin_id INT NOT NULL, start VARCHAR(255) NOT NULL, end VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_93875A49222586DC (bin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `range` ADD CONSTRAINT FK_93875A49222586DC FOREIGN KEY (bin_id) REFERENCES bin (id)');
        $this->addSql('ALTER TABLE ranges DROP FOREIGN KEY FK_149DB165222586DC');
        $this->addSql('DROP TABLE ranges');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ranges (id INT AUTO_INCREMENT NOT NULL, bin_id INT NOT NULL, start VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_149DB165222586DC (bin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ranges ADD CONSTRAINT FK_149DB165222586DC FOREIGN KEY (bin_id) REFERENCES bin (id)');
        $this->addSql('ALTER TABLE `range` DROP FOREIGN KEY FK_93875A49222586DC');
        $this->addSql('DROP TABLE `range`');
    }
}
