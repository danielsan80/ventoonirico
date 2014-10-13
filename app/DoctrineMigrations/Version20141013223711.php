<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141013223711 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE dan_group_audit");
        $this->addSql("DROP TABLE dan_user_audit");
        $this->addSql("DROP TABLE revisions");
        $this->addSql("ALTER TABLE dan_game ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL");
        $this->addSql("ALTER TABLE fos_user_user_group DROP FOREIGN KEY FK_B3C77447A76ED395");
        $this->addSql("ALTER TABLE fos_user_user_group DROP FOREIGN KEY FK_B3C77447FE54D947");
        $this->addSql("ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447A76ED395 FOREIGN KEY (user_id) REFERENCES dan_user (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447FE54D947 FOREIGN KEY (group_id) REFERENCES dan_group (id) ON DELETE CASCADE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE dan_group_audit (id INT NOT NULL, rev INT NOT NULL, name VARCHAR(255) DEFAULT NULL, roles LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', revtype VARCHAR(4) NOT NULL, PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE dan_user_audit (id INT NOT NULL, rev INT NOT NULL, username VARCHAR(255) DEFAULT NULL, username_canonical VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, email_canonical VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) DEFAULT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) DEFAULT NULL, expired TINYINT(1) DEFAULT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT DEFAULT NULL COMMENT '(DC2Type:array)', credentials_expired TINYINT(1) DEFAULT NULL, credentials_expire_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, date_of_birth DATETIME DEFAULT NULL, firstname VARCHAR(64) DEFAULT NULL, lastname VARCHAR(64) DEFAULT NULL, website VARCHAR(64) DEFAULT NULL, biography VARCHAR(255) DEFAULT NULL, gender VARCHAR(1) DEFAULT NULL, locale VARCHAR(8) DEFAULT NULL, timezone VARCHAR(64) DEFAULT NULL, phone VARCHAR(64) DEFAULT NULL, facebook_uid VARCHAR(255) DEFAULT NULL, facebook_name VARCHAR(255) DEFAULT NULL, facebook_data LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json)', twitter_uid VARCHAR(255) DEFAULT NULL, twitter_name VARCHAR(255) DEFAULT NULL, twitter_data LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json)', gplus_uid VARCHAR(255) DEFAULT NULL, gplus_name VARCHAR(255) DEFAULT NULL, gplus_data LONGTEXT DEFAULT NULL COMMENT '(DC2Type:json)', token VARCHAR(255) DEFAULT NULL, two_step_code VARCHAR(255) DEFAULT NULL, google_id VARCHAR(255) DEFAULT NULL, image LONGTEXT DEFAULT NULL, revtype VARCHAR(4) NOT NULL, google_access_token VARCHAR(255) DEFAULT NULL, facebook_id VARCHAR(255) DEFAULT NULL, facebook_access_token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE revisions (id INT AUTO_INCREMENT NOT NULL, timestamp DATETIME NOT NULL, username VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE dan_game DROP created_at, DROP updated_at");
        $this->addSql("ALTER TABLE fos_user_user_group DROP FOREIGN KEY FK_B3C77447A76ED395");
        $this->addSql("ALTER TABLE fos_user_user_group DROP FOREIGN KEY FK_B3C77447FE54D947");
        $this->addSql("ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447A76ED395 FOREIGN KEY (user_id) REFERENCES dan_user (id)");
        $this->addSql("ALTER TABLE fos_user_user_group ADD CONSTRAINT FK_B3C77447FE54D947 FOREIGN KEY (group_id) REFERENCES dan_group (id)");
    }
}
