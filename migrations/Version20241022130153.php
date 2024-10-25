<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241022130153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE step_establishment DROP FOREIGN KEY FK_B3096DE173B21E9C');
        $this->addSql('ALTER TABLE step_establishment DROP FOREIGN KEY FK_B3096DE18565851');
        $this->addSql('DROP TABLE step_establishment');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE step_establishment (step_id INT NOT NULL, establishment_id INT NOT NULL, INDEX IDX_B3096DE173B21E9C (step_id), INDEX IDX_B3096DE18565851 (establishment_id), PRIMARY KEY(step_id, establishment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE step_establishment ADD CONSTRAINT FK_B3096DE173B21E9C FOREIGN KEY (step_id) REFERENCES step (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE step_establishment ADD CONSTRAINT FK_B3096DE18565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id) ON DELETE CASCADE');
    }
}
