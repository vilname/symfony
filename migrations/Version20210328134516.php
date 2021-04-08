<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210328134516 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "appertice" (id BIGSERIAL NOT NULL, name VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE appertice_skill (appertice_id BIGINT NOT NULL, skill_id BIGINT NOT NULL, PRIMARY KEY(appertice_id, skill_id))');
        $this->addSql('CREATE INDEX IDX_877DDD48447EDF41 ON appertice_skill (appertice_id)');
        $this->addSql('CREATE INDEX IDX_877DDD485585C142 ON appertice_skill (skill_id)');
        $this->addSql('CREATE TABLE "group" (id BIGSERIAL NOT NULL, name VARCHAR(32) NOT NULL, skill_count INT DEFAULT NULL, active BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "group_item" (id BIGSERIAL NOT NULL, group_id BIGINT DEFAULT NULL, skill_group_item BIGINT DEFAULT NULL, appertice_group_item BIGINT DEFAULT NULL, teacher_group_item BIGINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_36417E6EFE54D947 ON "group_item" (group_id)');
        $this->addSql('CREATE INDEX IDX_36417E6E23C8A21C ON "group_item" (skill_group_item)');
        $this->addSql('CREATE INDEX IDX_36417E6EB8731584 ON "group_item" (appertice_group_item)');
        $this->addSql('CREATE INDEX IDX_36417E6EBC5EDCCE ON "group_item" (teacher_group_item)');
        $this->addSql('CREATE TABLE "skill" (id BIGSERIAL NOT NULL, skills VARCHAR(32) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "teachers" (id BIGSERIAL NOT NULL, name VARCHAR(32) NOT NULL, group_count INT DEFAULT NULL, skill_count INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE teacher_skill (teacher_id BIGINT NOT NULL, skill_id BIGINT NOT NULL, PRIMARY KEY(teacher_id, skill_id))');
        $this->addSql('CREATE INDEX IDX_FC2582A41807E1D ON teacher_skill (teacher_id)');
        $this->addSql('CREATE INDEX IDX_FC2582A5585C142 ON teacher_skill (skill_id)');
        $this->addSql('ALTER TABLE appertice_skill ADD CONSTRAINT FK_877DDD48447EDF41 FOREIGN KEY (appertice_id) REFERENCES "appertice" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appertice_skill ADD CONSTRAINT FK_877DDD485585C142 FOREIGN KEY (skill_id) REFERENCES "skill" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "group_item" ADD CONSTRAINT FK_36417E6EFE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "group_item" ADD CONSTRAINT FK_36417E6E23C8A21C FOREIGN KEY (skill_group_item) REFERENCES "skill" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "group_item" ADD CONSTRAINT FK_36417E6EB8731584 FOREIGN KEY (appertice_group_item) REFERENCES "appertice" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "group_item" ADD CONSTRAINT FK_36417E6EBC5EDCCE FOREIGN KEY (teacher_group_item) REFERENCES "teachers" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE teacher_skill ADD CONSTRAINT FK_FC2582A41807E1D FOREIGN KEY (teacher_id) REFERENCES "teachers" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE teacher_skill ADD CONSTRAINT FK_FC2582A5585C142 FOREIGN KEY (skill_id) REFERENCES "skill" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appertice_skill DROP CONSTRAINT FK_877DDD48447EDF41');
        $this->addSql('ALTER TABLE "group_item" DROP CONSTRAINT FK_36417E6EB8731584');
        $this->addSql('ALTER TABLE "group_item" DROP CONSTRAINT FK_36417E6EFE54D947');
        $this->addSql('ALTER TABLE appertice_skill DROP CONSTRAINT FK_877DDD485585C142');
        $this->addSql('ALTER TABLE "group_item" DROP CONSTRAINT FK_36417E6E23C8A21C');
        $this->addSql('ALTER TABLE teacher_skill DROP CONSTRAINT FK_FC2582A5585C142');
        $this->addSql('ALTER TABLE "group_item" DROP CONSTRAINT FK_36417E6EBC5EDCCE');
        $this->addSql('ALTER TABLE teacher_skill DROP CONSTRAINT FK_FC2582A41807E1D');
        $this->addSql('DROP TABLE "appertice"');
        $this->addSql('DROP TABLE appertice_skill');
        $this->addSql('DROP TABLE "group"');
        $this->addSql('DROP TABLE "group_item"');
        $this->addSql('DROP TABLE "skill"');
        $this->addSql('DROP TABLE "teachers"');
        $this->addSql('DROP TABLE teacher_skill');
    }
}
