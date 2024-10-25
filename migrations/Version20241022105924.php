<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241022105924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE step_establishment (step_id INT NOT NULL, establishment_id INT NOT NULL, INDEX IDX_B3096DE173B21E9C (step_id), INDEX IDX_B3096DE18565851 (establishment_id), PRIMARY KEY(step_id, establishment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE step_establishment ADD CONSTRAINT FK_B3096DE173B21E9C FOREIGN KEY (step_id) REFERENCES step (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE step_establishment ADD CONSTRAINT FK_B3096DE18565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE action DROP INDEX UNIQ_47CC8C92A76ED395, ADD INDEX IDX_47CC8C92A76ED395 (user_id)');
        $this->addSql('ALTER TABLE action ADD step_id INT NOT NULL, ADD establishment_id INT NOT NULL, ADD document_id INT NOT NULL, ADD comment VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C9273B21E9C FOREIGN KEY (step_id) REFERENCES step (id)');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C928565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id)');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C92C33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('CREATE INDEX IDX_47CC8C9273B21E9C ON action (step_id)');
        $this->addSql('CREATE INDEX IDX_47CC8C928565851 ON action (establishment_id)');
        $this->addSql('CREATE INDEX IDX_47CC8C92C33F7837 ON action (document_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE step_establishment DROP FOREIGN KEY FK_B3096DE173B21E9C');
        $this->addSql('ALTER TABLE step_establishment DROP FOREIGN KEY FK_B3096DE18565851');
        $this->addSql('DROP TABLE step_establishment');
        $this->addSql('ALTER TABLE action DROP INDEX IDX_47CC8C92A76ED395, ADD UNIQUE INDEX UNIQ_47CC8C92A76ED395 (user_id)');
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C9273B21E9C');
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C928565851');
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C92C33F7837');
        $this->addSql('DROP INDEX IDX_47CC8C9273B21E9C ON action');
        $this->addSql('DROP INDEX IDX_47CC8C928565851 ON action');
        $this->addSql('DROP INDEX IDX_47CC8C92C33F7837 ON action');
        $this->addSql('ALTER TABLE action DROP step_id, DROP establishment_id, DROP document_id, DROP comment, DROP created_at');
    }
}
