<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200322200827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE serialNumbers (serial_number INT AUTO_INCREMENT NOT NULL, thing INT DEFAULT NULL, task INT DEFAULT NULL, INDEX IDX_DA1485505B4C2C83 (thing), INDEX IDX_DA148550527EDB25 (task), PRIMARY KEY(serial_number)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE serialNumbers ADD CONSTRAINT FK_DA1485505B4C2C83 FOREIGN KEY (thing) REFERENCES thing (id)');
        $this->addSql('ALTER TABLE serialNumbers ADD CONSTRAINT FK_DA148550527EDB25 FOREIGN KEY (task) REFERENCES tasks (id)');
        $this->addSql('ALTER TABLE needs DROP FOREIGN KEY FK_6A59BEEEDA6A219');
        $this->addSql('DROP INDEX IDX_6A59BEEEDA6A219 ON needs');
        $this->addSql('ALTER TABLE needs ADD covered INT DEFAULT NULL, CHANGE place_id need INT DEFAULT NULL');
        $this->addSql('ALTER TABLE needs ADD CONSTRAINT FK_6A59BEEEE6F46C44 FOREIGN KEY (need) REFERENCES place (id)');
        $this->addSql('CREATE INDEX IDX_6A59BEEEE6F46C44 ON needs (need)');
        $this->addSql('ALTER TABLE user ADD nick_telegram VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_5B4C2C83D79572D9 ON thing');
        $this->addSql('ALTER TABLE thing ADD type VARCHAR(180) NOT NULL, ADD url_thingiverse VARCHAR(180) DEFAULT NULL, ADD other_url VARCHAR(180) DEFAULT NULL, ADD photo_url VARCHAR(180) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5B4C2C838CDE5729 ON thing (type)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE serialNumbers');
        $this->addSql('ALTER TABLE needs DROP FOREIGN KEY FK_6A59BEEEE6F46C44');
        $this->addSql('DROP INDEX IDX_6A59BEEEE6F46C44 ON needs');
        $this->addSql('ALTER TABLE needs ADD place_id INT DEFAULT NULL, DROP need, DROP covered');
        $this->addSql('ALTER TABLE needs ADD CONSTRAINT FK_6A59BEEEDA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('CREATE INDEX IDX_6A59BEEEDA6A219 ON needs (place_id)');
        $this->addSql('DROP INDEX UNIQ_5B4C2C838CDE5729 ON thing');
        $this->addSql('ALTER TABLE thing DROP type, DROP url_thingiverse, DROP other_url, DROP photo_url');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5B4C2C83D79572D9 ON thing (model)');
        $this->addSql('ALTER TABLE user DROP nick_telegram');
    }
}
