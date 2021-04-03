<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210403163407 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER INDEX idx_36417e6efe54d947 RENAME TO group_item__group_id__ind');
        $this->addSql('ALTER INDEX idx_36417e6e23c8a21c RENAME TO group_item__skill_group_item__ind');
        $this->addSql('ALTER INDEX idx_36417e6eb8731584 RENAME TO group_item__appertice_group_item__ind');
        $this->addSql('ALTER INDEX idx_36417e6ebc5edcce RENAME TO group_item__teacher_group_item__ind');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER INDEX group_item__skill_group_item__ind RENAME TO idx_36417e6e23c8a21c');
        $this->addSql('ALTER INDEX group_item__group_id__ind RENAME TO idx_36417e6efe54d947');
        $this->addSql('ALTER INDEX group_item__appertice_group_item__ind RENAME TO idx_36417e6eb8731584');
        $this->addSql('ALTER INDEX group_item__teacher_group_item__ind RENAME TO idx_36417e6ebc5edcce');
    }
}
