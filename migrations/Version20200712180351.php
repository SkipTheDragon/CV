<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200712180351 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_49FEA157CCFA12B8');
        $this->addSql('DROP INDEX IDX_49FEA157C54C8C93');
        $this->addSql('CREATE TEMPORARY TABLE __temp__component AS SELECT id, profile_id, type_id, title, location, position FROM component');
        $this->addSql('DROP TABLE component');
        $this->addSql('CREATE TABLE component (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, profile_id INTEGER DEFAULT NULL, type_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, location VARCHAR(255) NOT NULL COLLATE BINARY, position INTEGER NOT NULL, CONSTRAINT FK_49FEA157CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_49FEA157C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO component (id, profile_id, type_id, title, location, position) SELECT id, profile_id, type_id, title, location, position FROM __temp__component');
        $this->addSql('DROP TABLE __temp__component');
        $this->addSql('CREATE INDEX IDX_49FEA157CCFA12B8 ON component (profile_id)');
        $this->addSql('CREATE INDEX IDX_49FEA157C54C8C93 ON component (type_id)');
        $this->addSql('DROP INDEX IDX_5BF54558E2ABAFFF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__field AS SELECT id, component_id, position, data FROM field');
        $this->addSql('DROP TABLE field');
        $this->addSql('CREATE TABLE field (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, component_id INTEGER NOT NULL, position INTEGER NOT NULL, data CLOB NOT NULL COLLATE BINARY --(DC2Type:json)
        , CONSTRAINT FK_5BF54558E2ABAFFF FOREIGN KEY (component_id) REFERENCES component (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO field (id, component_id, position, data) SELECT id, component_id, position, data FROM __temp__field');
        $this->addSql('DROP TABLE __temp__field');
        $this->addSql('CREATE INDEX IDX_5BF54558E2ABAFFF ON field (component_id)');
        $this->addSql('ALTER TABLE profile ADD COLUMN cv VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE TEMPORARY TABLE __temp__type AS SELECT id, name, rules FROM type');
        $this->addSql('DROP TABLE type');
        $this->addSql('CREATE TABLE type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, rules CLOB DEFAULT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO type (id, name, rules) SELECT id, name, rules FROM __temp__type');
        $this->addSql('DROP TABLE __temp__type');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_49FEA157CCFA12B8');
        $this->addSql('DROP INDEX IDX_49FEA157C54C8C93');
        $this->addSql('CREATE TEMPORARY TABLE __temp__component AS SELECT id, profile_id, type_id, title, location, position FROM component');
        $this->addSql('DROP TABLE component');
        $this->addSql('CREATE TABLE component (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, profile_id INTEGER DEFAULT NULL, type_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, position INTEGER NOT NULL)');
        $this->addSql('INSERT INTO component (id, profile_id, type_id, title, location, position) SELECT id, profile_id, type_id, title, location, position FROM __temp__component');
        $this->addSql('DROP TABLE __temp__component');
        $this->addSql('CREATE INDEX IDX_49FEA157CCFA12B8 ON component (profile_id)');
        $this->addSql('CREATE INDEX IDX_49FEA157C54C8C93 ON component (type_id)');
        $this->addSql('DROP INDEX IDX_5BF54558E2ABAFFF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__field AS SELECT id, component_id, position, data FROM field');
        $this->addSql('DROP TABLE field');
        $this->addSql('CREATE TABLE field (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, component_id INTEGER NOT NULL, position INTEGER NOT NULL, data CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO field (id, component_id, position, data) SELECT id, component_id, position, data FROM __temp__field');
        $this->addSql('DROP TABLE __temp__field');
        $this->addSql('CREATE INDEX IDX_5BF54558E2ABAFFF ON field (component_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__profile AS SELECT id, name, role, quotation, profile_img, cvlink FROM profile');
        $this->addSql('DROP TABLE profile');
        $this->addSql('CREATE TABLE profile (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, quotation VARCHAR(255) DEFAULT NULL, profile_img VARCHAR(255) NOT NULL, cvlink VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO profile (id, name, role, quotation, profile_img, cvlink) SELECT id, name, role, quotation, profile_img, cvlink FROM __temp__profile');
        $this->addSql('DROP TABLE __temp__profile');
        $this->addSql('CREATE TEMPORARY TABLE __temp__type AS SELECT id, name, rules FROM type');
        $this->addSql('DROP TABLE type');
        $this->addSql('CREATE TABLE type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, rules CLOB DEFAULT \'NULL --(DC2Type:json)\' COLLATE BINARY --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO type (id, name, rules) SELECT id, name, rules FROM __temp__type');
        $this->addSql('DROP TABLE __temp__type');
    }
}
