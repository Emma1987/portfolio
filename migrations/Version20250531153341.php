<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250531153341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add a position field to the Tag entity and enforce uniqueness on the name field.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag ADD position INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_TAG_NAME ON tag (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_TAG_NAME ON tag');
        $this->addSql('ALTER TABLE tag DROP position');
    }
}
