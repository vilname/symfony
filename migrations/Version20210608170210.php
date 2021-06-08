<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210608170210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_skill (user_id BIGINT NOT NULL, skill_id BIGINT NOT NULL, PRIMARY KEY(user_id, skill_id))');
        $this->addSql('CREATE INDEX IDX_BCFF1F2FA76ED395 ON user_skill (user_id)');
        $this->addSql('CREATE INDEX IDX_BCFF1F2F5585C142 ON user_skill (skill_id)');
        $this->addSql('ALTER TABLE user_skill ADD CONSTRAINT FK_BCFF1F2FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_skill ADD CONSTRAINT FK_BCFF1F2F5585C142 FOREIGN KEY (skill_id) REFERENCES "skill" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE appertice_skill');
        $this->addSql('DROP TABLE group_appertice');
        $this->addSql('DROP TABLE group_teacher');
        $this->addSql('ALTER TABLE appertice ADD password VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE appertice ADD roles VARCHAR(1024) NOT NULL');
        $this->addSql('ALTER TABLE appertice RENAME COLUMN name TO login');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E6518763AA08CB10 ON appertice (login)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appertice_skill (appertice_id BIGINT NOT NULL, skill_id BIGINT NOT NULL, PRIMARY KEY(appertice_id, skill_id))');
        $this->addSql('CREATE INDEX idx_877ddd485585c142 ON appertice_skill (skill_id)');
        $this->addSql('CREATE INDEX idx_877ddd48447edf41 ON appertice_skill (appertice_id)');
        $this->addSql('CREATE TABLE group_appertice (group_id BIGINT NOT NULL, appertice_id BIGINT NOT NULL, PRIMARY KEY(group_id, appertice_id))');
        $this->addSql('CREATE INDEX idx_26e2db82447edf41 ON group_appertice (appertice_id)');
        $this->addSql('CREATE INDEX idx_26e2db82fe54d947 ON group_appertice (group_id)');
        $this->addSql('CREATE TABLE group_teacher (group_id BIGINT NOT NULL, teacher_id BIGINT NOT NULL, PRIMARY KEY(group_id, teacher_id))');
        $this->addSql('CREATE INDEX idx_36f6f2d9fe54d947 ON group_teacher (group_id)');
        $this->addSql('CREATE INDEX idx_36f6f2d941807e1d ON group_teacher (teacher_id)');
        $this->addSql('ALTER TABLE appertice_skill ADD CONSTRAINT fk_877ddd48447edf41 FOREIGN KEY (appertice_id) REFERENCES appertice (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE appertice_skill ADD CONSTRAINT fk_877ddd485585c142 FOREIGN KEY (skill_id) REFERENCES skill (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_appertice ADD CONSTRAINT fk_26e2db82fe54d947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_appertice ADD CONSTRAINT fk_26e2db82447edf41 FOREIGN KEY (appertice_id) REFERENCES appertice (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_teacher ADD CONSTRAINT fk_36f6f2d9fe54d947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE user_skill');
        $this->addSql('DROP INDEX UNIQ_E6518763AA08CB10');
        $this->addSql('ALTER TABLE appertice DROP password');
        $this->addSql('ALTER TABLE appertice DROP roles');
        $this->addSql('ALTER TABLE appertice RENAME COLUMN login TO name');
    }
}
