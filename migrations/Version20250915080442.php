<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250915080442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_mission (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, mission_id INT DEFAULT NULL, is_completed TINYINT(1) NOT NULL, completed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C86AEC36A76ED395 (user_id), INDEX IDX_C86AEC36BE6CAE90 (mission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_mission ADD CONSTRAINT FK_C86AEC36A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_mission ADD CONSTRAINT FK_C86AEC36BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_mission DROP FOREIGN KEY FK_C86AEC36A76ED395');
        $this->addSql('ALTER TABLE user_mission DROP FOREIGN KEY FK_C86AEC36BE6CAE90');
        $this->addSql('DROP TABLE user_mission');
    }
}
