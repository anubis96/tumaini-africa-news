<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260608162506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE aactivity (id INT AUTO_INCREMENT NOT NULL, categories_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, resume VARCHAR(255) DEFAULT NULL, date DATE NOT NULL COMMENT '(DC2Type:date_immutable)', lieu VARCHAR(255) NOT NULL, status VARCHAR(50) NOT NULL, image_icon VARCHAR(100) DEFAULT NULL, participants INT DEFAULT NULL, beneficiaires VARCHAR(255) DEFAULT NULL, INDEX IDX_6447577AA21214B7 (categories_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE acategory (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, icon VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, couleur VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE amembre (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, poste VARCHAR(100) DEFAULT NULL, bio LONGTEXT DEFAULT NULL, specialite VARCHAR(100) DEFAULT NULL, anciennete VARCHAR(10) DEFAULT NULL, initiales VARCHAR(5) DEFAULT NULL, couleur VARCHAR(50) DEFAULT NULL, linkedin VARCHAR(255) DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, telephone VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE aoffre (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, resume VARCHAR(255) DEFAULT NULL, type VARCHAR(100) DEFAULT NULL, lieu VARCHAR(255) DEFAULT NULL, date_limite DATE NOT NULL COMMENT '(DC2Type:date_immutable)', experience VARCHAR(255) DEFAULT NULL, formation VARCHAR(255) DEFAULT NULL, compentences JSON DEFAULT NULL, statut VARCHAR(50) NOT NULL, icon VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE aactivity ADD CONSTRAINT FK_6447577AA21214B7 FOREIGN KEY (categories_id) REFERENCES acategory (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE audio CHANGE audio_url audio_url VARCHAR(255) DEFAULT NULL, CHANGE cover cover VARCHAR(255) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE aactivity DROP FOREIGN KEY FK_6447577AA21214B7
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE aactivity
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE acategory
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE amembre
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE aoffre
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE audio CHANGE audio_url audio_url VARCHAR(255) NOT NULL, CHANGE cover cover VARCHAR(255) NOT NULL
        SQL);
    }
}
