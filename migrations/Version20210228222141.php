<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210228222141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial migration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE trip_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE country (code VARCHAR(3) NOT NULL, name VARCHAR(255) DEFAULT NULL, region VARCHAR(255) DEFAULT NULL, PRIMARY KEY(code))');
        $this->addSql('CREATE TABLE trip (id INT NOT NULL, user_id INT DEFAULT NULL, country_code CHAR(3) NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, notes VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX trip_start_date_end_date_index ON trip (start_date, end_date)');
        $this->addSql('CREATE INDEX trip_user_id_index ON trip (user_id)');
        $this->addSql('CREATE INDEX trip_country_code_index ON trip (country_code)');
        $this->addSql('CREATE INDEX trip_country_id_index ON trip (country_code)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53BA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE trip DROP CONSTRAINT FK_7656F53BA76ED395');
        $this->addSql('DROP SEQUENCE trip_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE trip');
        $this->addSql('DROP TABLE "user"');
    }
}
