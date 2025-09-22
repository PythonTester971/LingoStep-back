<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Custom migration to fix foreign key constraints for user deletion
 */
final class Version20250922150000 extends AbstractMigration
{
  public function getDescription(): string
  {
    return 'Fix foreign key constraints for user deletion';
  }

  public function up(Schema $schema): void
  {
    // Drop existing foreign keys safely - if they exist
    $this->addSql('SET FOREIGN_KEY_CHECKS=0');

    // Update AnsweredQuestion - Option relationship
    $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A4FA90DB2');
    $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A4FA90DB2 FOREIGN KEY (optione_id) REFERENCES `option` (id) ON DELETE SET NULL');

    // Update AnsweredQuestion - Question relationship
    $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A1E27F6BF');
    $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE SET NULL');

    // Update UserMission cascade settings if needed
    $this->addSql('ALTER TABLE user_mission DROP FOREIGN KEY FK_C86AEC7E59949888');
    $this->addSql('ALTER TABLE user_mission ADD CONSTRAINT FK_C86AEC7E59949888 FOREIGN KEY (user_language_course_id) REFERENCES user_language_course (id) ON DELETE CASCADE');

    $this->addSql('SET FOREIGN_KEY_CHECKS=1');
  }

  public function down(Schema $schema): void
  {
    $this->addSql('SET FOREIGN_KEY_CHECKS=0');

    // Revert AnsweredQuestion - Option relationship
    $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A4FA90DB2');
    $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A4FA90DB2 FOREIGN KEY (optione_id) REFERENCES `option` (id) ON DELETE RESTRICT');

    // Revert AnsweredQuestion - Question relationship
    $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A1E27F6BF');
    $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE RESTRICT');

    // Revert UserMission cascade settings if needed
    $this->addSql('ALTER TABLE user_mission DROP FOREIGN KEY FK_C86AEC7E59949888');
    $this->addSql('ALTER TABLE user_mission ADD CONSTRAINT FK_C86AEC7E59949888 FOREIGN KEY (user_language_course_id) REFERENCES user_language_course (id)');

    $this->addSql('SET FOREIGN_KEY_CHECKS=1');
  }
}
