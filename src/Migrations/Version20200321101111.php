<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200321101111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE needs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, place INTEGER DEFAULT NULL, thing INTEGER DEFAULT NULL, amount INTEGER NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A59BEEE741D53CD ON needs (place)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6A59BEEE5B4C2C83 ON needs (thing)');
        $this->addSql('CREATE TABLE thing (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, model VARCHAR(180) NOT NULL, description VARCHAR(180) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5B4C2C83D79572D9 ON thing (model)');
        $this->addSql('CREATE TABLE place (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_741D53CD5E237E06 ON place (name)');
        $this->addSql('CREATE TABLE tasks (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, maker INTEGER DEFAULT NULL, thing INTEGER DEFAULT NULL, place INTEGER DEFAULT NULL, amount INTEGER NOT NULL, delivery_type INTEGER NOT NULL, extra VARCHAR(255) DEFAULT NULL, delivery_date DATE DEFAULT NULL, status INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_50586597C6197FB4 ON tasks (maker)');
        $this->addSql('CREATE INDEX IDX_505865975B4C2C83 ON tasks (thing)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_50586597741D53CD ON tasks (place)');
        $this->addSql('ALTER TABLE user ADD COLUMN nick VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD COLUMN address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD COLUMN city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD COLUMN postal_code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD COLUMN phone_number INTEGER DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE needs');
        $this->addSql('DROP TABLE thing');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, email, roles, password) SELECT id, email, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }
}
