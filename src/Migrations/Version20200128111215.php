<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200128111215 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE avatar_id avatar_id INT DEFAULT NULL, CHANGE token token VARCHAR(255) DEFAULT NULL, CHANGE expired_token expired_token DATETIME DEFAULT NULL, CHANGE newsletter newsletter TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE700047D2');
        $this->addSql('DROP INDEX UNIQ_E00CEDDE700047D2 ON booking');
        $this->addSql('ALTER TABLE booking ADD user_id INT DEFAULT NULL, DROP ticket_id, CHANGE score score INT DEFAULT NULL, CHANGE pseudo pseudo VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDEA76ED395 ON booking (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEA76ED395');
        $this->addSql('DROP INDEX IDX_E00CEDDEA76ED395 ON booking');
        $this->addSql('ALTER TABLE booking ADD ticket_id INT DEFAULT NULL, DROP user_id, CHANGE score score INT DEFAULT NULL, CHANGE pseudo pseudo VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E00CEDDE700047D2 ON booking (ticket_id)');
        $this->addSql('ALTER TABLE user CHANGE avatar_id avatar_id INT DEFAULT NULL, CHANGE token token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE expired_token expired_token DATETIME DEFAULT \'NULL\', CHANGE newsletter newsletter TINYINT(1) DEFAULT \'NULL\'');
    }
}
