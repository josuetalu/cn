<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241022095501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE privileges');
        $this->addSql('ALTER TABLE document ADD step_id INT DEFAULT NULL, ADD is_back TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A7673B21E9C FOREIGN KEY (step_id) REFERENCES step (id)');
        $this->addSql('CREATE INDEX IDX_D8698A7673B21E9C ON document (step_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE privileges (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A7673B21E9C');
        $this->addSql('DROP INDEX IDX_D8698A7673B21E9C ON document');
        $this->addSql('ALTER TABLE document DROP step_id, DROP is_back');
    }
}
