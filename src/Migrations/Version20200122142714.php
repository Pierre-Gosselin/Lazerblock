<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200122142714 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE card (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, serial VARCHAR(255) NOT NULL, credits INT NOT NULL, expire_credits_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_161498D3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_gift (id INT AUTO_INCREMENT NOT NULL, cards_id INT NOT NULL, gifts_id INT NOT NULL, serial VARCHAR(255) NOT NULL, used TINYINT(1) NOT NULL, expired_at DATETIME NOT NULL, INDEX IDX_484F9BAFDC555177 (cards_id), INDEX IDX_484F9BAF9357C6E5 (gifts_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gift (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) NOT NULL, price INT NOT NULL, picture VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE card_gift ADD CONSTRAINT FK_484F9BAFDC555177 FOREIGN KEY (cards_id) REFERENCES card_gift (id)');
        $this->addSql('ALTER TABLE card_gift ADD CONSTRAINT FK_484F9BAF9357C6E5 FOREIGN KEY (gifts_id) REFERENCES gift (id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE token token VARCHAR(255) DEFAULT NULL, CHANGE expired_token expired_token DATETIME DEFAULT NULL, CHANGE avatar avatar VARCHAR(255) DEFAULT NULL, CHANGE newsletter newsletter TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE card_gift DROP FOREIGN KEY FK_484F9BAFDC555177');
        $this->addSql('ALTER TABLE card_gift DROP FOREIGN KEY FK_484F9BAF9357C6E5');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE card_gift');
        $this->addSql('DROP TABLE gift');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE token token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE expired_token expired_token DATETIME DEFAULT \'NULL\', CHANGE avatar avatar VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE newsletter newsletter TINYINT(1) DEFAULT \'NULL\'');
    }
}
