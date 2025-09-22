<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250922141553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A1E27F6BF');
        $this->addSql('ALTER TABLE answered_question CHANGE question_id question_id INT NOT NULL');
        $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A4FA90DB2 FOREIGN KEY (optione_id) REFERENCES `option` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A4FA90DB2');
        $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A1E27F6BF');
        $this->addSql('ALTER TABLE answered_question CHANGE question_id question_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
