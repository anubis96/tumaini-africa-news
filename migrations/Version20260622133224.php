<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260622133224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE aactivity (id INT AUTO_INCREMENT NOT NULL, categories_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, resume VARCHAR(255) DEFAULT NULL, date DATE NOT NULL COMMENT '(DC2Type:date_immutable)', image_url VARCHAR(255) DEFAULT NULL, lieu VARCHAR(255) NOT NULL, status VARCHAR(50) NOT NULL, image_icon VARCHAR(100) DEFAULT NULL, participants INT DEFAULT NULL, beneficiaires VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_6447577AA21214B7 (categories_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE acategory (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, icon VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, couleur VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE advertise (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, image_url VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', company VARCHAR(255) NOT NULL, is_middle TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE agallery (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, image_names JSON DEFAULT NULL, temp_image_file VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE amembre (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, poste VARCHAR(100) DEFAULT NULL, bio LONGTEXT DEFAULT NULL, specialite VARCHAR(100) DEFAULT NULL, anciennete VARCHAR(10) DEFAULT NULL, initiales VARCHAR(5) DEFAULT NULL, couleur VARCHAR(50) DEFAULT NULL, linkedin VARCHAR(255) DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, telephone VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE analytics (id INT AUTO_INCREMENT NOT NULL, session_id VARCHAR(255) NOT NULL, ip_adress VARCHAR(50) NOT NULL, user_agent VARCHAR(255) DEFAULT NULL, device_type VARCHAR(50) NOT NULL, device_brand VARCHAR(100) DEFAULT NULL, os VARCHAR(50) DEFAULT NULL, browser VARCHAR(50) DEFAULT NULL, country_code VARCHAR(10) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, page_url VARCHAR(255) NOT NULL, page_title VARCHAR(255) NOT NULL, referrer VARCHAR(255) DEFAULT NULL, visited_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', country VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE aoffre (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, resume VARCHAR(255) DEFAULT NULL, type VARCHAR(100) DEFAULT NULL, lieu VARCHAR(255) DEFAULT NULL, date_limite DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', experience VARCHAR(255) DEFAULT NULL, formation VARCHAR(255) DEFAULT NULL, compentences JSON DEFAULT NULL, statut VARCHAR(50) NOT NULL, icon VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, category_id INT DEFAULT NULL, title LONGTEXT NOT NULL, content LONGTEXT DEFAULT NULL, image_url VARCHAR(255) DEFAULT NULL, published_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', created_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug LONGTEXT DEFAULT NULL, excerpt LONGTEXT DEFAULT NULL, is_published TINYINT(1) DEFAULT NULL, is_urgent TINYINT(1) DEFAULT NULL, view_count INT DEFAULT 0 NOT NULL, emission_url VARCHAR(255) DEFAULT NULL, geo_scope VARCHAR(50) DEFAULT NULL, geo_country VARCHAR(100) DEFAULT NULL, geo_country_code VARCHAR(10) DEFAULT NULL, geo_region VARCHAR(100) DEFAULT NULL, geo_city VARCHAR(100) DEFAULT NULL, geo_continent VARCHAR(50) DEFAULT NULL, INDEX IDX_23A0E66F675F31B (author_id), INDEX IDX_23A0E6612469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE article_tag (article_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_919694F97294869C (article_id), INDEX IDX_919694F9BAD26311 (tag_id), PRIMARY KEY(article_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE audio (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, audio_url VARCHAR(255) DEFAULT NULL, cover VARCHAR(255) DEFAULT NULL, duration INT NOT NULL, published_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', slug VARCHAR(255) NOT NULL, INDEX IDX_187D3695F675F31B (author_id), INDEX IDX_187D369512469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, lastname VARCHAR(100) NOT NULL, firstname VARCHAR(100) NOT NULL, phone VARCHAR(20) NOT NULL, email VARCHAR(255) DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', is_approved TINYINT(1) DEFAULT 0 NOT NULL, likes INT DEFAULT 0 NOT NULL, relation VARCHAR(255) DEFAULT NULL, is_rejected TINYINT(1) DEFAULT 0 NOT NULL, ip_address VARCHAR(45) DEFAULT NULL, user_agent VARCHAR(255) DEFAULT NULL, INDEX IDX_9474526C7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, article_id INT DEFAULT NULL, audio_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', likes INT NOT NULL, dislikes INT NOT NULL, INDEX IDX_5F9E962AF675F31B (author_id), INDEX IDX_5F9E962A7294869C (article_id), INDEX IDX_5F9E962A3A3123C7 (audio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, usage_count INT DEFAULT 0, created_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', is_active TINYINT(1) DEFAULT 1 NOT NULL, category VARCHAR(50) DEFAULT NULL, color VARCHAR(7) DEFAULT NULL, UNIQUE INDEX UNIQ_389B7835E237E06 (name), UNIQUE INDEX UNIQ_389B783989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, username VARCHAR(255) NOT NULL, is_active TINYINT(1) DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE aactivity ADD CONSTRAINT FK_6447577AA21214B7 FOREIGN KEY (categories_id) REFERENCES acategory (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ADD CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article ADD CONSTRAINT FK_23A0E6612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article_tag ADD CONSTRAINT FK_919694F97294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article_tag ADD CONSTRAINT FK_919694F9BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE audio ADD CONSTRAINT FK_187D3695F675F31B FOREIGN KEY (author_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE audio ADD CONSTRAINT FK_187D369512469DE2 FOREIGN KEY (category_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment ADD CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AF675F31B FOREIGN KEY (author_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A7294869C FOREIGN KEY (article_id) REFERENCES article (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A3A3123C7 FOREIGN KEY (audio_id) REFERENCES audio (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE aactivity DROP FOREIGN KEY FK_6447577AA21214B7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article DROP FOREIGN KEY FK_23A0E6612469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article_tag DROP FOREIGN KEY FK_919694F97294869C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE article_tag DROP FOREIGN KEY FK_919694F9BAD26311
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE audio DROP FOREIGN KEY FK_187D3695F675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE audio DROP FOREIGN KEY FK_187D369512469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7294869C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AF675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A7294869C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A3A3123C7
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE aactivity
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE acategory
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE advertise
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE agallery
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE amembre
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE analytics
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE aoffre
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE article
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE article_tag
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE audio
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE comment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE comments
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tag
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
