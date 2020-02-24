<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200224064954 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, sujet VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking CHANGE user_id user_id INT DEFAULT NULL, CHANGE score score INT DEFAULT NULL, CHANGE pseudo pseudo VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE event CHANGE multiplicator multiplicator DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE gift CHANGE category category enum(\'Friandises\', \'Costumes\')');
        $this->addSql('ALTER TABLE user CHANGE avatar_id avatar_id INT DEFAULT NULL, CHANGE token token VARCHAR(255) DEFAULT NULL, CHANGE expired_token expired_token DATETIME DEFAULT NULL, CHANGE newsletter newsletter TINYINT(1) DEFAULT NULL, CHANGE side side enum(\'Jedi\', \'Sith\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE contact');
        $this->addSql('ALTER TABLE booking CHANGE user_id user_id INT DEFAULT NULL, CHANGE score score INT DEFAULT NULL, CHANGE pseudo pseudo VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE event CHANGE multiplicator multiplicator DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE gift CHANGE category category VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE avatar_id avatar_id INT DEFAULT NULL, CHANGE token token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE expired_token expired_token DATETIME DEFAULT \'NULL\', CHANGE newsletter newsletter TINYINT(1) DEFAULT \'NULL\', CHANGE side side VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
