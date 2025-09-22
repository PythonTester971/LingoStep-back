<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250922130054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A4FA90DB2');
        $this->addSql('ALTER TABLE answered_question CHANGE optione_id optione_id INT NOT NULL');
        $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A4FA90DB2 FOREIGN KEY (optione_id) REFERENCES `option` (id) ON DELETE RESTRICT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A4FA90DB2');
        $this->addSql('ALTER TABLE answered_question CHANGE optione_id optione_id INT NOT NULL');
        $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A4FA90DB2 FOREIGN KEY (optione_id) REFERENCES `option` (id) ON DELETE RESTRICT');
    }
}
