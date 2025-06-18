<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250602155254 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Order project technologies & tags';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_technology DROP FOREIGN KEY FK_ECC5297F166D1F9C');
        $this->addSql('ALTER TABLE project_technology DROP FOREIGN KEY FK_ECC5297F4235D463');
        $this->addSql('ALTER TABLE project_technology ADD id INT AUTO_INCREMENT NOT NULL, ADD position INT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE project_technology ADD CONSTRAINT FK_ECC5297F166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_technology ADD CONSTRAINT FK_ECC5297F4235D463 FOREIGN KEY (technology_id) REFERENCES technology (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_PROJECT_TECHNOLOGY ON project_technology (project_id, technology_id)');

        $this->addSql('ALTER TABLE project_tag DROP FOREIGN KEY FK_91F26D60166D1F9C');
        $this->addSql('ALTER TABLE project_tag DROP FOREIGN KEY FK_91F26D60BAD26311');
        $this->addSql('ALTER TABLE project_tag ADD id INT AUTO_INCREMENT NOT NULL, ADD position INT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE project_tag ADD CONSTRAINT FK_91F26D60166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_tag ADD CONSTRAINT FK_91F26D60BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_PROJECT_TAG ON project_tag (project_id, tag_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_technology MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE project_technology DROP FOREIGN KEY FK_ECC5297F166D1F9C');
        $this->addSql('ALTER TABLE project_technology DROP FOREIGN KEY FK_ECC5297F4235D463');
        $this->addSql('DROP INDEX UNIQ_PROJECT_TECHNOLOGY ON project_technology');
        $this->addSql('DROP INDEX `PRIMARY` ON project_technology');
        $this->addSql('ALTER TABLE project_technology DROP id, DROP position');
        $this->addSql('ALTER TABLE project_technology ADD CONSTRAINT FK_ECC5297F166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_technology ADD CONSTRAINT FK_ECC5297F4235D463 FOREIGN KEY (technology_id) REFERENCES technology (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_technology ADD PRIMARY KEY (project_id, technology_id)');

        $this->addSql('ALTER TABLE project_tag MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE project_tag DROP FOREIGN KEY FK_91F26D60166D1F9C');
        $this->addSql('ALTER TABLE project_tag DROP FOREIGN KEY FK_91F26D60BAD26311');
        $this->addSql('DROP INDEX UNIQ_PROJECT_TAG ON project_tag');
        $this->addSql('DROP INDEX `PRIMARY` ON project_tag');
        $this->addSql('ALTER TABLE project_tag DROP id, DROP position');
        $this->addSql('ALTER TABLE project_tag ADD CONSTRAINT FK_91F26D60166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_tag ADD CONSTRAINT FK_91F26D60BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_tag ADD PRIMARY KEY (project_id, tag_id)');
    }
}
