<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260618152718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE article ADD geo_region VARCHAR(255) DEFAULT NULL, ADD geo_placename VARCHAR(255) DEFAULT NULL, ADD geo_latitude VARCHAR(100) DEFAULT NULL, ADD geo_longitude VARCHAR(100) DEFAULT NULL, ADD geo_country VARCHAR(50) DEFAULT NULL, ADD geo_country_code VARCHAR(10) DEFAULT NULL, ADD geo_continent VARCHAR(50) DEFAULT NULL, ADD geo_scope VARCHAR(50) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE article DROP geo_region, DROP geo_placename, DROP geo_latitude, DROP geo_longitude, DROP geo_country, DROP geo_country_code, DROP geo_continent, DROP geo_scope
        SQL);
    }
}
