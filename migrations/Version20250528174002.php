<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250528174002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add a position field to the Technology entity and enforce uniqueness on the name field.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE technology ADD position INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_TECHNOLOGY_NAME ON technology (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE technology DROP position');
        $this->addSql('DROP INDEX UNIQ_TECHNOLOGY_NAME ON technology');
    }
}
