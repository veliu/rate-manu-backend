<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Food\Ingredient\FoodIngredient;

final readonly class FoodIngredientResponse
{
    public function __construct(
        #[OA\Property(description: 'Unique identifier of the food ingredient', type: 'string', format: 'uuid')]
        public Uuid $id,
        #[OA\Property(description: 'Unique identifier of the food', type: 'string', format: 'uuid')]
        public Uuid $foodId,
        #[OA\Property(description: 'Unique identifier of the ingredient', type: 'string', format: 'uuid')]
        public Uuid $ingredientId,
        #[OA\Property(description: 'Ingredient details')]
        public IngredientResponse $ingredient,
        #[OA\Property(description: 'Amount of the ingredient', type: 'number', format: 'float', minimum: 0)]
        public float $amount,
        #[OA\Property(description: 'Unit of measurement for the ingredient amount', type: 'string')]
        public string $unit,
    ) {
    }

    public static function fromEntity(FoodIngredient $entity): self
    {
        return new self(
            $entity->id,
            $entity->food->id,
            $entity->ingredient->id,
            IngredientResponse::fromEntity($entity->ingredient),
            $entity->amount,
            $entity->unit->value,
        );
    }
}
