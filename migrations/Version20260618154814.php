<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260618154814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE article ADD geo_city VARCHAR(100) DEFAULT NULL, DROP geo_placename, DROP geo_latitude, DROP geo_longitude, CHANGE geo_region geo_region VARCHAR(100) DEFAULT NULL, CHANGE geo_country geo_country VARCHAR(100) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE article ADD geo_placename VARCHAR(255) DEFAULT NULL, ADD geo_longitude VARCHAR(100) DEFAULT NULL, CHANGE geo_country geo_country VARCHAR(50) DEFAULT NULL, CHANGE geo_region geo_region VARCHAR(255) DEFAULT NULL, CHANGE geo_city geo_latitude VARCHAR(100) DEFAULT NULL
        SQL);
    }
}
