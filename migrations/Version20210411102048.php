<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210411102048 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_item ADD appertice_group_item BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE group_item ADD CONSTRAINT FK_36417E6EB8731584 FOREIGN KEY (appertice_group_item) REFERENCES "appertice" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_36417E6EB8731584 ON group_item (appertice_group_item)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "group_item" DROP CONSTRAINT FK_36417E6EB8731584');
        $this->addSql('DROP INDEX IDX_36417E6EB8731584');
        $this->addSql('ALTER TABLE "group_item" DROP appertice_group_item');
    }
}
