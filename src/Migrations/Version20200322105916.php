<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200322105916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_6A59BEEEC36906A7');
        $this->addSql('DROP INDEX IDX_6A59BEEEDA6A219');
        $this->addSql('CREATE TEMPORARY TABLE __temp__needs AS SELECT id, place_id, thing_id, amount FROM needs');
        $this->addSql('DROP TABLE needs');
        $this->addSql('CREATE TABLE needs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, place_id INTEGER DEFAULT NULL, thing_id INTEGER DEFAULT NULL, amount INTEGER NOT NULL, covered INTEGER DEFAULT NULL, CONSTRAINT FK_6A59BEEEDA6A219 FOREIGN KEY (place_id) REFERENCES place (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6A59BEEEC36906A7 FOREIGN KEY (thing_id) REFERENCES thing (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO needs (id, place_id, thing_id, amount) SELECT id, place_id, thing_id, amount FROM __temp__needs');
        $this->addSql('DROP TABLE __temp__needs');
        $this->addSql('CREATE INDEX IDX_6A59BEEEC36906A7 ON needs (thing_id)');
        $this->addSql('CREATE INDEX IDX_6A59BEEEDA6A219 ON needs (place_id)');
        $this->addSql('DROP INDEX IDX_50586597DA6A219');
        $this->addSql('DROP INDEX IDX_505865975B4C2C83');
        $this->addSql('DROP INDEX IDX_50586597C6197FB4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__tasks AS SELECT id, maker, thing, place_id, amount, delivery_type, extra, delivery_date, collect_address, status FROM tasks');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('CREATE TABLE tasks (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, maker INTEGER DEFAULT NULL, thing INTEGER DEFAULT NULL, place_id INTEGER DEFAULT NULL, amount INTEGER NOT NULL, delivery_type INTEGER NOT NULL, extra VARCHAR(255) DEFAULT NULL COLLATE BINARY, delivery_date DATE DEFAULT NULL, collect_address VARCHAR(255) DEFAULT NULL COLLATE BINARY, status INTEGER NOT NULL, CONSTRAINT FK_50586597C6197FB4 FOREIGN KEY (maker) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_505865975B4C2C83 FOREIGN KEY (thing) REFERENCES thing (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_50586597DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO tasks (id, maker, thing, place_id, amount, delivery_type, extra, delivery_date, collect_address, status) SELECT id, maker, thing, place_id, amount, delivery_type, extra, delivery_date, collect_address, status FROM __temp__tasks');
        $this->addSql('DROP TABLE __temp__tasks');
        $this->addSql('CREATE INDEX IDX_50586597DA6A219 ON tasks (place_id)');
        $this->addSql('CREATE INDEX IDX_505865975B4C2C83 ON tasks (thing)');
        $this->addSql('CREATE INDEX IDX_50586597C6197FB4 ON tasks (maker)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('sqlite' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_6A59BEEEDA6A219');
        $this->addSql('DROP INDEX IDX_6A59BEEEC36906A7');
        $this->addSql('CREATE TEMPORARY TABLE __temp__needs AS SELECT id, place_id, thing_id, amount FROM needs');
        $this->addSql('DROP TABLE needs');
        $this->addSql('CREATE TABLE needs (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, place_id INTEGER DEFAULT NULL, thing_id INTEGER DEFAULT NULL, amount INTEGER NOT NULL)');
        $this->addSql('INSERT INTO needs (id, place_id, thing_id, amount) SELECT id, place_id, thing_id, amount FROM __temp__needs');
        $this->addSql('DROP TABLE __temp__needs');
        $this->addSql('CREATE INDEX IDX_6A59BEEEDA6A219 ON needs (place_id)');
        $this->addSql('CREATE INDEX IDX_6A59BEEEC36906A7 ON needs (thing_id)');
        $this->addSql('DROP INDEX IDX_50586597C6197FB4');
        $this->addSql('DROP INDEX IDX_505865975B4C2C83');
        $this->addSql('DROP INDEX IDX_50586597DA6A219');
        $this->addSql('CREATE TEMPORARY TABLE __temp__tasks AS SELECT id, maker, thing, place_id, amount, delivery_type, extra, delivery_date, collect_address, status FROM tasks');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('CREATE TABLE tasks (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, maker INTEGER DEFAULT NULL, thing INTEGER DEFAULT NULL, place_id INTEGER DEFAULT NULL, amount INTEGER NOT NULL, delivery_type INTEGER NOT NULL, extra VARCHAR(255) DEFAULT NULL, delivery_date DATE DEFAULT NULL, collect_address VARCHAR(255) DEFAULT NULL, status INTEGER NOT NULL)');
        $this->addSql('INSERT INTO tasks (id, maker, thing, place_id, amount, delivery_type, extra, delivery_date, collect_address, status) SELECT id, maker, thing, place_id, amount, delivery_type, extra, delivery_date, collect_address, status FROM __temp__tasks');
        $this->addSql('DROP TABLE __temp__tasks');
        $this->addSql('CREATE INDEX IDX_50586597C6197FB4 ON tasks (maker)');
        $this->addSql('CREATE INDEX IDX_505865975B4C2C83 ON tasks (thing)');
        $this->addSql('CREATE INDEX IDX_50586597DA6A219 ON tasks (place_id)');
    }
}
