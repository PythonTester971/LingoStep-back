<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250918101459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answered_question DROP INDEX UNIQ_E0F04F5A4FA90DB2, ADD INDEX IDX_E0F04F5A4FA90DB2 (optione_id)');
        $this->addSql('ALTER TABLE answered_question DROP INDEX UNIQ_E0F04F5A1E27F6BF, ADD INDEX IDX_E0F04F5A1E27F6BF (question_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answered_question DROP INDEX IDX_E0F04F5A4FA90DB2, ADD UNIQUE INDEX UNIQ_E0F04F5A4FA90DB2 (optione_id)');
        $this->addSql('ALTER TABLE answered_question DROP INDEX IDX_E0F04F5A1E27F6BF, ADD UNIQUE INDEX UNIQ_E0F04F5A1E27F6BF (question_id)');
    }
}
