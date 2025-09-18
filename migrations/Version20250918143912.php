<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250918143912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE language_course_user DROP FOREIGN KEY FK_26777EFD9F7CB278');
        $this->addSql('ALTER TABLE language_course_user DROP FOREIGN KEY FK_26777EFDA76ED395');
        $this->addSql('DROP TABLE language_course_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE language_course_user (language_course_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_26777EFD9F7CB278 (language_course_id), INDEX IDX_26777EFDA76ED395 (user_id), PRIMARY KEY(language_course_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE language_course_user ADD CONSTRAINT FK_26777EFD9F7CB278 FOREIGN KEY (language_course_id) REFERENCES language_course (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE language_course_user ADD CONSTRAINT FK_26777EFDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
