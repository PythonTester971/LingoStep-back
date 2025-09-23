<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Clean migration to fix cascading relationships
 */
final class Version20250922152400 extends AbstractMigration
{
  public function getDescription(): string
  {
    return 'Fix entity relationships for cascading deletion';
  }

  public function up(Schema $schema): void
  {
    // Turn off foreign key checks temporarily
    $this->addSql('SET FOREIGN_KEY_CHECKS=0');

    // Fix answered_question constraints
    $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A1E27F6BF');
    $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A4FA90DB2');
    $this->addSql('ALTER TABLE answered_question CHANGE optione_id optione_id INT DEFAULT NULL, CHANGE question_id question_id INT DEFAULT NULL');
    $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A4FA90DB2 FOREIGN KEY (optione_id) REFERENCES `option` (id) ON DELETE SET NULL');
    $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE SET NULL');

    // Fix user_mission cascade
    $this->addSql('ALTER TABLE user_mission DROP FOREIGN KEY FK_C86AEC7E59949888');
    $this->addSql('ALTER TABLE user_mission ADD CONSTRAINT FK_C86AEC7E59949888 FOREIGN KEY (user_language_course_id) REFERENCES user_language_course (id) ON DELETE CASCADE');

    $this->addSql('SET FOREIGN_KEY_CHECKS=1');
  }

  public function down(Schema $schema): void
  {
    // Turn off foreign key checks temporarily
    $this->addSql('SET FOREIGN_KEY_CHECKS=0');

    // Revert answered_question constraints
    $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A4FA90DB2');
    $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A1E27F6BF');
    $this->addSql('ALTER TABLE answered_question CHANGE optione_id optione_id INT NOT NULL, CHANGE question_id question_id INT NOT NULL');
    $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A4FA90DB2 FOREIGN KEY (optione_id) REFERENCES `option` (id) ON DELETE RESTRICT');
    $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');

    // Revert user_mission cascade
    $this->addSql('ALTER TABLE user_mission DROP FOREIGN KEY FK_C86AEC7E59949888');
    $this->addSql('ALTER TABLE user_mission ADD CONSTRAINT FK_C86AEC7E59949888 FOREIGN KEY (user_language_course_id) REFERENCES user_language_course (id)');

    $this->addSql('SET FOREIGN_KEY_CHECKS=1');
  }
}
