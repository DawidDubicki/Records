<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230302231233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utwory DROP CONSTRAINT fk_67ccbd0ac96e53dd');
        $this->addSql('DROP INDEX idx_67ccbd0ac96e53dd');
        $this->addSql('ALTER TABLE utwory ADD artysta VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE utwory RENAME COLUMN artysta_id TO nosnik_id');
        $this->addSql('ALTER TABLE utwory ADD CONSTRAINT FK_67CCBD0A7518210F FOREIGN KEY (nosnik_id) REFERENCES nosnik (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_67CCBD0A7518210F ON utwory (nosnik_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE utwory DROP CONSTRAINT FK_67CCBD0A7518210F');
        $this->addSql('DROP INDEX IDX_67CCBD0A7518210F');
        $this->addSql('ALTER TABLE utwory DROP artysta');
        $this->addSql('ALTER TABLE utwory RENAME COLUMN nosnik_id TO artysta_id');
        $this->addSql('ALTER TABLE utwory ADD CONSTRAINT fk_67ccbd0ac96e53dd FOREIGN KEY (artysta_id) REFERENCES nosnik (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_67ccbd0ac96e53dd ON utwory (artysta_id)');
    }
}
