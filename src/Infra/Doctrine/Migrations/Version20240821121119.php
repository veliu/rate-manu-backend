<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240821121119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_group_relation (id UUID NOT NULL, user_id UUID DEFAULT NULL, group_id UUID DEFAULT NULL, role VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3CD46FA76ED395 ON user_group_relation (user_id)');
        $this->addSql('CREATE INDEX IDX_3CD46FFE54D947 ON user_group_relation (group_id)');
        $this->addSql('CREATE UNIQUE INDEX user_group ON user_group_relation (user_id, group_id)');
        $this->addSql('COMMENT ON COLUMN user_group_relation.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_group_relation.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_group_relation.group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE user_group_relation ADD CONSTRAINT FK_3CD46FA76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_group_relation ADD CONSTRAINT FK_3CD46FFE54D947 FOREIGN KEY (group_id) REFERENCES app_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_user ALTER email TYPE VARCHAR(320)');
        $this->addSql('ALTER TABLE app_user ALTER email TYPE VARCHAR(320)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_group_relation DROP CONSTRAINT FK_3CD46FA76ED395');
        $this->addSql('ALTER TABLE user_group_relation DROP CONSTRAINT FK_3CD46FFE54D947');
        $this->addSql('DROP TABLE user_group_relation');
        $this->addSql('ALTER TABLE app_user ALTER email TYPE VARCHAR(320)');
    }
}
