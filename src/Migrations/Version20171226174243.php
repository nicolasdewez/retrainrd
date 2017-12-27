<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171226174243 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE stops_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE stops (id SMALLINT NOT NULL, code VARCHAR(30) NOT NULL, name VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, coordinates POINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN stops.coordinates IS \'(DC2Type:point)\'');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, roles TEXT NOT NULL, enabled BOOLEAN NOT NULL, registration_state VARCHAR(15) NOT NULL, registration_code VARCHAR(255) NOT NULL, email_notification BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE INDEX user_email ON users (email)');
        $this->addSql('CREATE INDEX user_registration_code ON users (registration_code)');
        $this->addSql('COMMENT ON COLUMN users.roles IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('postgresql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE stops_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('DROP TABLE stops');
        $this->addSql('DROP TABLE users');
    }
}
