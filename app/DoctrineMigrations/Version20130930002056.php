<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130930002056 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE dan_desire_user (id INT AUTO_INCREMENT NOT NULL, desire_id INT DEFAULT NULL, user_id INT DEFAULT NULL, note LONGTEXT NOT NULL, INDEX IDX_50AA3E019B1C5641 (desire_id), INDEX IDX_50AA3E01A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE dan_desire_user ADD CONSTRAINT FK_50AA3E019B1C5641 FOREIGN KEY (desire_id) REFERENCES dan_desire (id)");
        $this->addSql("ALTER TABLE dan_desire_user ADD CONSTRAINT FK_50AA3E01A76ED395 FOREIGN KEY (user_id) REFERENCES dan_user (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE dan_desire_user");
    }
}
