<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250831190708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Delete cascade on food_ingredient';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE food_ingredient DROP CONSTRAINT FK_CEAC8D1933FE08C');
        $this->addSql('ALTER TABLE food_ingredient DROP CONSTRAINT FK_CEAC8D1BA8E87C4');
        $this->addSql('ALTER TABLE food_ingredient ADD CONSTRAINT FK_CEAC8D1933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE food_ingredient ADD CONSTRAINT FK_CEAC8D1BA8E87C4 FOREIGN KEY (food_id) REFERENCES food (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE food_ingredient DROP CONSTRAINT fk_ceac8d1ba8e87c4');
        $this->addSql('ALTER TABLE food_ingredient DROP CONSTRAINT fk_ceac8d1933fe08c');
        $this->addSql('ALTER TABLE food_ingredient ADD CONSTRAINT fk_ceac8d1ba8e87c4 FOREIGN KEY (food_id) REFERENCES food (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE food_ingredient ADD CONSTRAINT fk_ceac8d1933fe08c FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
