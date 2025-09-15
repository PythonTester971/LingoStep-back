<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250912144043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answered_question (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, optione_id INT DEFAULT NULL, question_id INT DEFAULT NULL, mission_id INT DEFAULT NULL, INDEX IDX_E0F04F5AA76ED395 (user_id), UNIQUE INDEX UNIQ_E0F04F5A4FA90DB2 (optione_id), UNIQUE INDEX UNIQ_E0F04F5A1E27F6BF (question_id), INDEX IDX_E0F04F5ABE6CAE90 (mission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, flag_path VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language_course (id INT AUTO_INCREMENT NOT NULL, language_id INT NOT NULL, label VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, progress DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_A8D56DF82F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language_course_user (language_course_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_26777EFD9F7CB278 (language_course_id), INDEX IDX_26777EFDA76ED395 (user_id), PRIMARY KEY(language_course_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission (id INT AUTO_INCREMENT NOT NULL, language_course_id INT NOT NULL, label VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', xp_reward INT NOT NULL, INDEX IDX_9067F23C9F7CB278 (language_course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, code VARCHAR(255) DEFAULT NULL, is_correct TINYINT(1) NOT NULL, INDEX IDX_5A8600B01E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, mission_id INT NOT NULL, instruction LONGTEXT NOT NULL, is_answered TINYINT(1) DEFAULT NULL, INDEX IDX_B6F7494EBE6CAE90 (mission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, language_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, picture_path VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', xp INT DEFAULT NULL, INDEX IDX_8D93D64982F1BAF4 (language_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A4FA90DB2 FOREIGN KEY (optione_id) REFERENCES `option` (id)');
        $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5A1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE answered_question ADD CONSTRAINT FK_E0F04F5ABE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('ALTER TABLE language_course ADD CONSTRAINT FK_A8D56DF82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE language_course_user ADD CONSTRAINT FK_26777EFD9F7CB278 FOREIGN KEY (language_course_id) REFERENCES language_course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE language_course_user ADD CONSTRAINT FK_26777EFDA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C9F7CB278 FOREIGN KEY (language_course_id) REFERENCES language_course (id)');
        $this->addSql('ALTER TABLE `option` ADD CONSTRAINT FK_5A8600B01E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EBE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D64982F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5AA76ED395');
        $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A4FA90DB2');
        $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5A1E27F6BF');
        $this->addSql('ALTER TABLE answered_question DROP FOREIGN KEY FK_E0F04F5ABE6CAE90');
        $this->addSql('ALTER TABLE language_course DROP FOREIGN KEY FK_A8D56DF82F1BAF4');
        $this->addSql('ALTER TABLE language_course_user DROP FOREIGN KEY FK_26777EFD9F7CB278');
        $this->addSql('ALTER TABLE language_course_user DROP FOREIGN KEY FK_26777EFDA76ED395');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C9F7CB278');
        $this->addSql('ALTER TABLE `option` DROP FOREIGN KEY FK_5A8600B01E27F6BF');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EBE6CAE90');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64982F1BAF4');
        $this->addSql('DROP TABLE answered_question');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE language_course');
        $this->addSql('DROP TABLE language_course_user');
        $this->addSql('DROP TABLE mission');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
