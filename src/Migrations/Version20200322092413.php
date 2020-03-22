<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200322092413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE needs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, place_id INTEGER DEFAULT NULL, thing_id INTEGER DEFAULT NULL, amount INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_6A59BEEEDA6A219 ON needs (place_id)');
        $this->addSql('CREATE INDEX IDX_6A59BEEEC36906A7 ON needs (thing_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, nick VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, phone_number INTEGER DEFAULT NULL, nick_telegram VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE TABLE thing (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(180) NOT NULL, model VARCHAR(180) NOT NULL, description VARCHAR(180) DEFAULT NULL, url_thingiverse VARCHAR(180) DEFAULT NULL, other_url VARCHAR(180) DEFAULT NULL, photo_url VARCHAR(180) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5B4C2C838CDE5729 ON thing (type)');
        $this->addSql('CREATE TABLE place (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_741D53CD5E237E06 ON place (name)');
        $this->addSql('CREATE TABLE tasks (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, maker INTEGER DEFAULT NULL, thing INTEGER DEFAULT NULL, place_id INTEGER DEFAULT NULL, amount INTEGER NOT NULL, delivery_type INTEGER NOT NULL, extra VARCHAR(255) DEFAULT NULL, delivery_date DATE DEFAULT NULL, collect_address VARCHAR(255) DEFAULT NULL, status INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_50586597C6197FB4 ON tasks (maker)');
        $this->addSql('CREATE INDEX IDX_505865975B4C2C83 ON tasks (thing)');
        $this->addSql('CREATE INDEX IDX_50586597DA6A219 ON tasks (place_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE needs');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE thing');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE tasks');
    }
}
