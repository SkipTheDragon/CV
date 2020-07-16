<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200710200954 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE component (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, profile_id INTEGER DEFAULT NULL, type_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, position INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_49FEA157CCFA12B8 ON component (profile_id)');
        $this->addSql('CREATE INDEX IDX_49FEA157C54C8C93 ON component (type_id)');
        $this->addSql('CREATE TABLE field (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, component_id INTEGER NOT NULL, position INTEGER NOT NULL, data CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE INDEX IDX_5BF54558E2ABAFFF ON field (component_id)');
        $this->addSql('CREATE TABLE profile (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, quotation VARCHAR(255) DEFAULT NULL, profile_img VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, rules CLOB DEFAULT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6495E237E06 ON user (name)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE component');
        $this->addSql('DROP TABLE field');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE user');
    }
}
