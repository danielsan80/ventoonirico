<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130926142323 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE dan_user ADD google_access_token VARCHAR(255) DEFAULT NULL, CHANGE google_id google_id VARCHAR(255) DEFAULT NULL");
        $this->addSql("ALTER TABLE dan_user_audit ADD google_access_token VARCHAR(255) DEFAULT NULL, CHANGE google_id google_id VARCHAR(255) DEFAULT NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE dan_user DROP google_access_token, CHANGE google_id google_id VARCHAR(40) DEFAULT NULL");
        $this->addSql("ALTER TABLE dan_user_audit DROP google_access_token, CHANGE google_id google_id VARCHAR(40) DEFAULT NULL");
    }
}
