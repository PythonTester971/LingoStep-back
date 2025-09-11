<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250911191649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answered_question (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, optione_id INT DEFAULT NULL, question_id INT DEFAULT NULL, mission_id INT DEFAULT NULL, INDEX IDX_E0F04F5AA76ED395 (user_id), UNIQUE INDEX UNIQ_E0F04F5A4FA90DB2 (optione_id), UNIQUE INDEX UNIQ_E0F04F5A1E27F6BF (question_id), INDEX IDX_E0F04F5ABE6CAE90 (mission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A4FA90DB2 FOREIGN KEY (optione_id) REFERENCES `option` (id)');
        $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5ABE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5AA76ED395');
        $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A4FA90DB2');
        $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A1E27F6BF');
        $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5ABE6CAE90');
        $this->addSql('DROP TABLE answered_question');
    }
}
