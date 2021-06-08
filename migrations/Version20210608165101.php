<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210608165101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE teacher_skill DROP CONSTRAINT fk_fc2582a41807e1d');
        $this->addSql('ALTER TABLE group_teacher DROP CONSTRAINT fk_36f6f2d941807e1d');
        $this->addSql('DROP SEQUENCE teachers_id_seq CASCADE');
        $this->addSql('CREATE TABLE teacher (id BIGSERIAL NOT NULL, login VARCHAR(32) NOT NULL, password VARCHAR(120) NOT NULL, roles VARCHAR(1024) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B0F6A6D5AA08CB10 ON teacher (login)');
        $this->addSql('DROP TABLE teachers');
        $this->addSql('DROP TABLE teacher_skill');

        $this->addSql('ALTER TABLE group_teacher ADD CONSTRAINT FK_36F6F2D941807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_teacher DROP CONSTRAINT FK_36F6F2D941807E1D');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_teacher DROP CONSTRAINT FK_36F6F2D941807E1D');
        $this->addSql('CREATE SEQUENCE teachers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE teachers (id BIGSERIAL NOT NULL, name VARCHAR(32) NOT NULL, group_count INT DEFAULT NULL, skill_count INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE teacher_skill (teacher_id BIGINT NOT NULL, skill_id BIGINT NOT NULL, PRIMARY KEY(teacher_id, skill_id))');
        $this->addSql('CREATE INDEX idx_fc2582a5585c142 ON teacher_skill (skill_id)');
        $this->addSql('CREATE INDEX idx_fc2582a41807e1d ON teacher_skill (teacher_id)');
        $this->addSql('ALTER TABLE teacher_skill ADD CONSTRAINT fk_fc2582a41807e1d FOREIGN KEY (teacher_id) REFERENCES teachers (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE teacher_skill ADD CONSTRAINT fk_fc2582a5585c142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE teacher');
        $this->addSql('ALTER TABLE group_teacher DROP CONSTRAINT fk_36f6f2d941807e1d');
        $this->addSql('ALTER TABLE group_teacher ADD CONSTRAINT fk_36f6f2d941807e1d FOREIGN KEY (teacher_id) REFERENCES teachers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
