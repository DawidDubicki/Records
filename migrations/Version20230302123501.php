<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230302123501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nosnik ALTER nosnik TYPE VARCHAR(5)');
        $this->addSql('ALTER TABLE nosnik ALTER nosnik DROP DEFAULT');
        $this->addSql('ALTER TABLE nosnik ALTER lista_utworow TYPE TEXT');
        $this->addSql('ALTER TABLE nosnik ALTER lista_utworow DROP DEFAULT');
        $this->addSql('ALTER TABLE nosnik ALTER lista_utworow TYPE TEXT');
        $this->addSql('COMMENT ON COLUMN nosnik.nosnik IS NULL');
        $this->addSql('COMMENT ON COLUMN nosnik.lista_utworow IS \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE nosnik ALTER nosnik TYPE TEXT');
        $this->addSql('ALTER TABLE nosnik ALTER nosnik DROP DEFAULT');
        $this->addSql('ALTER TABLE nosnik ALTER nosnik TYPE TEXT');
        $this->addSql('ALTER TABLE nosnik ALTER lista_utworow TYPE VARCHAR(5)');
        $this->addSql('ALTER TABLE nosnik ALTER lista_utworow DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN nosnik.nosnik IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN nosnik.lista_utworow IS NULL');
    }
}
