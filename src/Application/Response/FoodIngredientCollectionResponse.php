<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Food\Ingredient\FoodIngredientEntityCollection;

final readonly class FoodIngredientCollectionResponse
{
    public function __construct(
        #[OA\Property(description: 'Unique identifier of the food', type: 'string', format: 'uuid')]
        public Uuid $foodId,
        #[OA\Property(
            description: 'List of food ingredients',
            type: 'array',
            items: new OA\Items(ref: new Model(type: FoodIngredientResponse::class))
        )]
        public array $ingredients,
    ) {
    }

    public static function fromEntityCollection(Uuid $foodId, FoodIngredientEntityCollection $entityCollection): self
    {
        $ingredients = [];

        foreach ($entityCollection as $entity) {
            $ingredients[] = FoodIngredientResponse::fromEntity($entity);
        }

        return new self($foodId, $ingredients);
    }
}
