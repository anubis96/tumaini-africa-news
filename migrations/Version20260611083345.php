<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260611083345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE agallery (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE gallery_image (id INT AUTO_INCREMENT NOT NULL, gallery_id INT DEFAULT NULL, filename VARCHAR(255) NOT NULL, original_name VARCHAR(255) DEFAULT NULL, mime_type VARCHAR(50) NOT NULL, file_size INT NOT NULL, width INT DEFAULT NULL, height INT DEFAULT NULL, thumbnail VARCHAR(20) DEFAULT NULL, position INT NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_21A0D47C4E7AF8F (gallery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gallery_image ADD CONSTRAINT FK_21A0D47C4E7AF8F FOREIGN KEY (gallery_id) REFERENCES agallery (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE gallery_image DROP FOREIGN KEY FK_21A0D47C4E7AF8F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE agallery
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE gallery_image
        SQL);
    }
}
