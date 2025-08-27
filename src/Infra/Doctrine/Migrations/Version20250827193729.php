<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250827193729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE food_ingredient (id UUID NOT NULL, food_id UUID DEFAULT NULL, ingredient_id UUID DEFAULT NULL, unit VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CEAC8D1BA8E87C4 ON food_ingredient (food_id)');
        $this->addSql('CREATE INDEX IDX_CEAC8D1933FE08C ON food_ingredient (ingredient_id)');
        $this->addSql('CREATE UNIQUE INDEX food_ingredient_constraint ON food_ingredient (food_id, ingredient_id)');
        $this->addSql('COMMENT ON COLUMN food_ingredient.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN food_ingredient.food_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN food_ingredient.ingredient_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE ingredient (id UUID NOT NULL, user_id UUID DEFAULT NULL, group_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, default_unit VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6BAF7870A76ED395 ON ingredient (user_id)');
        $this->addSql('CREATE INDEX IDX_6BAF7870FE54D947 ON ingredient (group_id)');
        $this->addSql('COMMENT ON COLUMN ingredient.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ingredient.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ingredient.group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE food_ingredient ADD CONSTRAINT FK_CEAC8D1BA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE food_ingredient ADD CONSTRAINT FK_CEAC8D1933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF7870A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF7870FE54D947 FOREIGN KEY (group_id) REFERENCES app_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE app_user ALTER email TYPE VARCHAR(320)');
        $this->addSql('ALTER TABLE app_user ALTER email TYPE VARCHAR(320)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE food_ingredient DROP CONSTRAINT FK_CEAC8D1BA8E87C4');
        $this->addSql('ALTER TABLE food_ingredient DROP CONSTRAINT FK_CEAC8D1933FE08C');
        $this->addSql('ALTER TABLE ingredient DROP CONSTRAINT FK_6BAF7870A76ED395');
        $this->addSql('ALTER TABLE ingredient DROP CONSTRAINT FK_6BAF7870FE54D947');
        $this->addSql('DROP TABLE food_ingredient');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('ALTER TABLE app_user ALTER email TYPE VARCHAR(320)');
    }
}
