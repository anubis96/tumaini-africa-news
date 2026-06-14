<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260614154719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE analytics (id INT AUTO_INCREMENT NOT NULL, session_id VARCHAR(255) NOT NULL, ip_adress VARCHAR(50) NOT NULL, user_agent VARCHAR(255) DEFAULT NULL, device_type VARCHAR(50) NOT NULL, device_brand VARCHAR(100) DEFAULT NULL, os VARCHAR(50) DEFAULT NULL, browser VARCHAR(50) DEFAULT NULL, country_code VARCHAR(10) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, page_url VARCHAR(255) NOT NULL, page_title VARCHAR(255) NOT NULL, referrer VARCHAR(255) DEFAULT NULL, visited_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', country VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE analytics
        SQL);
    }
}
