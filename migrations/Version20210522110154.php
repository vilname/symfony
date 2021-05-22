<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210522110154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE group_appertice (group_id BIGINT NOT NULL, appertice_id BIGINT NOT NULL, PRIMARY KEY(group_id, appertice_id))');
        $this->addSql('CREATE INDEX IDX_26E2DB82FE54D947 ON group_appertice (group_id)');
        $this->addSql('CREATE INDEX IDX_26E2DB82447EDF41 ON group_appertice (appertice_id)');
        $this->addSql('CREATE TABLE group_skill (group_id BIGINT NOT NULL, skill_id BIGINT NOT NULL, PRIMARY KEY(group_id, skill_id))');
        $this->addSql('CREATE INDEX IDX_E11CF10FE54D947 ON group_skill (group_id)');
        $this->addSql('CREATE INDEX IDX_E11CF105585C142 ON group_skill (skill_id)');
        $this->addSql('CREATE TABLE group_teacher (group_id BIGINT NOT NULL, teacher_id BIGINT NOT NULL, PRIMARY KEY(group_id, teacher_id))');
        $this->addSql('CREATE INDEX IDX_36F6F2D9FE54D947 ON group_teacher (group_id)');
        $this->addSql('CREATE INDEX IDX_36F6F2D941807E1D ON group_teacher (teacher_id)');
        $this->addSql('ALTER TABLE group_appertice ADD CONSTRAINT FK_26E2DB82FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_appertice ADD CONSTRAINT FK_26E2DB82447EDF41 FOREIGN KEY (appertice_id) REFERENCES "appertice" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_skill ADD CONSTRAINT FK_E11CF10FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_skill ADD CONSTRAINT FK_E11CF105585C142 FOREIGN KEY (skill_id) REFERENCES "skill" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_teacher ADD CONSTRAINT FK_36F6F2D9FE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_teacher ADD CONSTRAINT FK_36F6F2D941807E1D FOREIGN KEY (teacher_id) REFERENCES "teachers" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE group_appertice');
        $this->addSql('DROP TABLE group_skill');
        $this->addSql('DROP TABLE group_teacher');
    }
}
