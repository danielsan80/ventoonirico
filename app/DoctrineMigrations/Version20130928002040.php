<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130928002040 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE dan_game CHANGE owners owners LONGTEXT NOT NULL COMMENT '(DC2Type:simple_array)'");
        $this->addSql("ALTER TABLE dan_desire CHANGE game_id game_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE dan_desire ADD CONSTRAINT FK_BC88B643E48FD905 FOREIGN KEY (game_id) REFERENCES dan_game (id)");
        $this->addSql("CREATE INDEX IDX_BC88B643E48FD905 ON dan_desire (game_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE dan_desire DROP FOREIGN KEY FK_BC88B643E48FD905");
        $this->addSql("DROP INDEX IDX_BC88B643E48FD905 ON dan_desire");
        $this->addSql("ALTER TABLE dan_desire CHANGE game_id game_id LONGTEXT NOT NULL");
        $this->addSql("ALTER TABLE dan_game CHANGE owners owners LONGTEXT NOT NULL COMMENT '(DC2Type:array)'");
    }
}
