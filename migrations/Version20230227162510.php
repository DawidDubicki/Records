<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230227162510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE nosnik_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE utwory_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE nosnik (id INT NOT NULL, artysta VARCHAR(255) NOT NULL, tytul VARCHAR(255) NOT NULL, nosnik TEXT NOT NULL, rok_wydania DATE NOT NULL, lista_utworow TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN nosnik.nosnik IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN nosnik.lista_utworow IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE utwory (id INT NOT NULL, artysta_id INT NOT NULL, tytul_utworu VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_67CCBD0AC96E53DD ON utwory (artysta_id)');
        $this->addSql('ALTER TABLE utwory ADD CONSTRAINT FK_67CCBD0AC96E53DD FOREIGN KEY (artysta_id) REFERENCES nosnik (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE utwory DROP CONSTRAINT FK_67CCBD0AC96E53DD');
        $this->addSql('DROP SEQUENCE nosnik_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE utwory_id_seq CASCADE');
        $this->addSql('DROP TABLE nosnik');
        $this->addSql('DROP TABLE utwory');
    }
}
