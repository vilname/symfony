<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210325182224 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appertice_skills (appertice_id BIGINT NOT NULL, skills_id BIGINT NOT NULL, PRIMARY KEY(appertice_id, skills_id))');
        $this->addSql('CREATE INDEX IDX_638E7B74447EDF41 ON appertice_skills (appertice_id)');
        $this->addSql('CREATE INDEX IDX_638E7B747FF61858 ON appertice_skills (skills_id)');
        $this->addSql('ALTER TABLE appertice_skills ADD CONSTRAINT FK_638E7B74447EDF41 FOREIGN KEY (appertice_id) REFERENCES "appertice" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appertice_skills ADD CONSTRAINT FK_638E7B747FF61858 FOREIGN KEY (skills_id) REFERENCES "skills" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "group" ADD skill_count INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE appertice_skills');
        $this->addSql('ALTER TABLE "group" DROP skill_count');
    }
}
