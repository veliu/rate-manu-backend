<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240821142737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users_groups DROP CONSTRAINT fk_ff8ab7e0a76ed395');
        $this->addSql('ALTER TABLE users_groups DROP CONSTRAINT fk_ff8ab7e0fe54d947');
        $this->addSql('ALTER TABLE group_user DROP CONSTRAINT fk_a4c98d39fe54d947');
        $this->addSql('ALTER TABLE group_user DROP CONSTRAINT fk_a4c98d39a76ed395');
        $this->addSql('DROP TABLE users_groups');
        $this->addSql('DROP TABLE group_user');
        $this->addSql('ALTER TABLE app_user ALTER email TYPE VARCHAR(320)');
        $this->addSql('ALTER TABLE app_user ALTER email TYPE VARCHAR(320)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE users_groups (user_id UUID NOT NULL, group_id UUID NOT NULL, PRIMARY KEY(user_id, group_id))');
        $this->addSql('CREATE INDEX idx_ff8ab7e0fe54d947 ON users_groups (group_id)');
        $this->addSql('CREATE INDEX idx_ff8ab7e0a76ed395 ON users_groups (user_id)');
        $this->addSql('COMMENT ON COLUMN users_groups.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users_groups.group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE group_user (group_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(group_id, user_id))');
        $this->addSql('CREATE INDEX idx_a4c98d39a76ed395 ON group_user (user_id)');
        $this->addSql('CREATE INDEX idx_a4c98d39fe54d947 ON group_user (group_id)');
        $this->addSql('COMMENT ON COLUMN group_user.group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN group_user.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT fk_ff8ab7e0a76ed395 FOREIGN KEY (user_id) REFERENCES app_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT fk_ff8ab7e0fe54d947 FOREIGN KEY (group_id) REFERENCES app_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_user ADD CONSTRAINT fk_a4c98d39fe54d947 FOREIGN KEY (group_id) REFERENCES app_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_user ADD CONSTRAINT fk_a4c98d39a76ed395 FOREIGN KEY (user_id) REFERENCES app_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_user ALTER email TYPE VARCHAR(320)');
    }
}
