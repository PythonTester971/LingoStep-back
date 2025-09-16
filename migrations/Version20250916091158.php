<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250916091158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_language_course (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, language_course_id INT DEFAULT NULL, progress INT NOT NULL, INDEX IDX_18513A50A76ED395 (user_id), INDEX IDX_18513A509F7CB278 (language_course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_language_course ADD CONSTRAINT FK_18513A50A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_language_course ADD CONSTRAINT FK_18513A509F7CB278 FOREIGN KEY (language_course_id) REFERENCES language_course (id)');
        $this->addSql('ALTER TABLE user_mission ADD user_language_course_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_mission ADD CONSTRAINT FK_C86AEC3633385483 FOREIGN KEY (user_language_course_id) REFERENCES user_language_course (id)');
        $this->addSql('CREATE INDEX IDX_C86AEC3633385483 ON user_mission (user_language_course_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_mission DROP FOREIGN KEY FK_C86AEC3633385483');
        $this->addSql('ALTER TABLE user_language_course DROP FOREIGN KEY FK_18513A50A76ED395');
        $this->addSql('ALTER TABLE user_language_course DROP FOREIGN KEY FK_18513A509F7CB278');
        $this->addSql('DROP TABLE user_language_course');
        $this->addSql('DROP INDEX IDX_C86AEC3633385483 ON user_mission');
        $this->addSql('ALTER TABLE user_mission DROP user_language_course_id');
    }
}
