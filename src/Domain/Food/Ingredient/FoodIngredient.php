<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food\Ingredient;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\EntityInterface;
use Veliu\RateManu\Domain\Food\Food;
use Veliu\RateManu\Domain\Ingredient\Ingredient;
use Veliu\RateManu\Domain\Ingredient\UnitEnum;

#[ORM\Entity]
#[ORM\UniqueConstraint(name: 'food_ingredient_constraint', columns: ['food_id', 'ingredient_id'])]
class FoodIngredient implements EntityInterface
{
    use TimestampableEntity;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        public readonly Uuid $id,

        #[ORM\ManyToOne(targetEntity: Food::class)]
        #[ORM\JoinColumn(name: 'food_id', referencedColumnName: 'id')]
        public readonly Food $food,

        #[ORM\ManyToOne(targetEntity: Ingredient::class)]
        #[ORM\JoinColumn(name: 'ingredient_id', referencedColumnName: 'id')]
        public readonly Ingredient $ingredient,

        #[ORM\Column(enumType: UnitEnum::class)]
        public readonly UnitEnum $unit,

        #[ORM\Column]
        public readonly float $amount,
    ) {
        $now = new \DateTime('now');
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public static function getName(): string
    {
        return 'FoodIngredient';
    }
}
