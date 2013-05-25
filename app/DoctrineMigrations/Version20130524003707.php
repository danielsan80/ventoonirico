<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130524003707 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE dan_desire DROP FOREIGN KEY FK_BC88B643A76ED395");
        $this->addSql("DROP INDEX IDX_BC88B643A76ED395 ON dan_desire");
        $this->addSql("ALTER TABLE dan_desire CHANGE user_id owner_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE dan_desire ADD CONSTRAINT FK_BC88B6437E3C61F9 FOREIGN KEY (owner_id) REFERENCES dan_user (id)");
        $this->addSql("CREATE INDEX IDX_BC88B6437E3C61F9 ON dan_desire (owner_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE dan_desire DROP FOREIGN KEY FK_BC88B6437E3C61F9");
        $this->addSql("DROP INDEX IDX_BC88B6437E3C61F9 ON dan_desire");
        $this->addSql("ALTER TABLE dan_desire CHANGE owner_id user_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE dan_desire ADD CONSTRAINT FK_BC88B643A76ED395 FOREIGN KEY (user_id) REFERENCES dan_user (id)");
        $this->addSql("CREATE INDEX IDX_BC88B643A76ED395 ON dan_desire (user_id)");
    }
}
