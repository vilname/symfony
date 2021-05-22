<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210522115157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE group_item_id_seq CASCADE');
        $this->addSql('DROP TABLE group_item');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE group_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE group_item (id BIGSERIAL NOT NULL, appertice BIGINT DEFAULT NULL, group_id BIGINT NOT NULL, skill BIGINT DEFAULT NULL, teacher BIGINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX group_item__appertice__ind ON group_item (appertice)');
        $this->addSql('CREATE INDEX group_item__group_id__ind ON group_item (group_id)');
        $this->addSql('CREATE INDEX group_item__skill__ind ON group_item (skill)');
        $this->addSql('CREATE INDEX group_item__teacher__ind ON group_item (teacher)');
        $this->addSql('ALTER TABLE group_item ADD CONSTRAINT fk_36417e6ee6518763 FOREIGN KEY (appertice) REFERENCES appertice (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_item ADD CONSTRAINT fk_36417e6efe54d947 FOREIGN KEY (group_id) REFERENCES "group" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_item ADD CONSTRAINT fk_36417e6e5e3de477 FOREIGN KEY (skill) REFERENCES skill (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_item ADD CONSTRAINT fk_36417e6eb0f6a6d5 FOREIGN KEY (teacher) REFERENCES teachers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
