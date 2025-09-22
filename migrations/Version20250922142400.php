<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250922142400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('SET FOREIGN_KEY_CHECKS=0');

        // Drop any existing foreign keys first
        $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A4FA90DB2');
        $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A1E27F6BF');

        // Change column types to allow NULL
        $this->addSql('ALTER TABLE answered_question CHANGE optione_id optione_id INT DEFAULT NULL, CHANGE question_id question_id INT DEFAULT NULL');

        // Re-add the foreign keys with ON DELETE SET NULL
        $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A4FA90DB2 FOREIGN KEY (optione_id) REFERENCES `option` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE SET NULL');

        $this->addSql('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A4FA90DB2');
        $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A1E27F6BF');
        $this->addSql('ALTER TABLE answered_question CHANGE optione_id optione_id INT NOT NULL, CHANGE question_id question_id INT NOT NULL');
    }
}
