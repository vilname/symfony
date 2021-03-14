<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210314083058 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE appeartice_skills (id BIGSERIAL NOT NULL, appertice_id BIGINT DEFAULT NULL, skill_id BIGINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX appertice_skills__appertice_id__ind ON appeartice_skills (appertice_id)');
        $this->addSql('CREATE INDEX appertice_skills__skill_id__ind ON appeartice_skills (skill_id)');
        $this->addSql('CREATE TABLE "appertice" (id BIGSERIAL NOT NULL, name VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "group" (id BIGSERIAL NOT NULL, name VARCHAR(32) NOT NULL, active BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "group_item" (id BIGSERIAL NOT NULL, group_id BIGINT DEFAULT NULL, appertice_id BIGINT DEFAULT NULL, teacher_id BIGINT DEFAULT NULL, skill_id BIGINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_36417E6EFE54D947 ON "group_item" (group_id)');
        $this->addSql('CREATE INDEX IDX_36417E6E447EDF41 ON "group_item" (appertice_id)');
        $this->addSql('CREATE INDEX IDX_36417E6E41807E1D ON "group_item" (teacher_id)');
        $this->addSql('CREATE INDEX IDX_36417E6E5585C142 ON "group_item" (skill_id)');
        $this->addSql('CREATE TABLE "skills" (id BIGSERIAL NOT NULL, skills VARCHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "teachers" (id BIGSERIAL NOT NULL, name VARCHAR(32) NOT NULL, skill_count INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "teachers_slills" (id BIGSERIAL NOT NULL, teacher_id BIGINT DEFAULT NULL, skill_id BIGINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX teacher__teacher_id__ind ON "teachers_slills" (teacher_id)');
        $this->addSql('CREATE INDEX teacher_skills__skill_id__ind ON "teachers_slills" (skill_id)');
        $this->addSql('ALTER TABLE appeartice_skills ADD CONSTRAINT FK_D87ECA49447EDF41 FOREIGN KEY (appertice_id) REFERENCES "appertice" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appeartice_skills ADD CONSTRAINT FK_D87ECA495585C142 FOREIGN KEY (skill_id) REFERENCES "skills" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "group_item" ADD CONSTRAINT FK_36417E6EFE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "group_item" ADD CONSTRAINT FK_36417E6E447EDF41 FOREIGN KEY (appertice_id) REFERENCES "appertice" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "group_item" ADD CONSTRAINT FK_36417E6E41807E1D FOREIGN KEY (teacher_id) REFERENCES "teachers" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "group_item" ADD CONSTRAINT FK_36417E6E5585C142 FOREIGN KEY (skill_id) REFERENCES "skills" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "teachers_slills" ADD CONSTRAINT FK_FD39D7BB41807E1D FOREIGN KEY (teacher_id) REFERENCES "teachers" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "teachers_slills" ADD CONSTRAINT FK_FD39D7BB5585C142 FOREIGN KEY (skill_id) REFERENCES "skills" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE appeartice_skills DROP CONSTRAINT FK_D87ECA49447EDF41');
        $this->addSql('ALTER TABLE "group_item" DROP CONSTRAINT FK_36417E6E447EDF41');
        $this->addSql('ALTER TABLE "group_item" DROP CONSTRAINT FK_36417E6EFE54D947');
        $this->addSql('ALTER TABLE appeartice_skills DROP CONSTRAINT FK_D87ECA495585C142');
        $this->addSql('ALTER TABLE "group_item" DROP CONSTRAINT FK_36417E6E5585C142');
        $this->addSql('ALTER TABLE "teachers_slills" DROP CONSTRAINT FK_FD39D7BB5585C142');
        $this->addSql('ALTER TABLE "group_item" DROP CONSTRAINT FK_36417E6E41807E1D');
        $this->addSql('ALTER TABLE "teachers_slills" DROP CONSTRAINT FK_FD39D7BB41807E1D');
        $this->addSql('DROP TABLE appeartice_skills');
        $this->addSql('DROP TABLE "appertice"');
        $this->addSql('DROP TABLE "group"');
        $this->addSql('DROP TABLE "group_item"');
        $this->addSql('DROP TABLE "skills"');
        $this->addSql('DROP TABLE "teachers"');
        $this->addSql('DROP TABLE "teachers_slills"');
    }
}
