<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200122155004 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494ACC9A20');
        $this->addSql('DROP INDEX UNIQ_8D93D6494ACC9A20 ON user');
        $this->addSql('ALTER TABLE user DROP card_id, CHANGE roles roles JSON NOT NULL, CHANGE token token VARCHAR(255) DEFAULT NULL, CHANGE expired_token expired_token DATETIME DEFAULT NULL, CHANGE avatar avatar VARCHAR(255) DEFAULT NULL, CHANGE newsletter newsletter TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD card_id INT NOT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE token token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE expired_token expired_token DATETIME DEFAULT \'NULL\', CHANGE avatar avatar VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE newsletter newsletter TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6494ACC9A20 ON user (card_id)');
    }
}
