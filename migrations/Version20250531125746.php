<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250531125746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add a position field to the Company entity and enforce uniqueness on the name field.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company ADD position INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_COMPANY_NAME ON company (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_COMPANY_NAME ON company');
        $this->addSql('ALTER TABLE company DROP position');
    }
}
