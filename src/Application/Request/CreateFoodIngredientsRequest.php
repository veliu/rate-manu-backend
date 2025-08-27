<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Veliu\RateManu\Domain\Ingredient\Command\CreateIngredient;

final readonly class CreateFoodIngredientsRequest
{
    #[OA\Property(
        description: 'List of food ingredients',
        type: 'array',
        items: new OA\Items(ref: new Model(type: CreateFoodIngredientRequest::class))
    )]
    #[Assert\Valid]
    #[Assert\NotBlank]
    public array $ingredients;

    public function __construct(array $ingredients,
    ) {
        $this->ingredients = array_map(
            fn (array $ingredient) => new CreateFoodIngredientRequest(
                $ingredient['id'] ?? null,
                $ingredient['ingredientId'] ?? '',
                $ingredient['unit'] ?? null,
                $ingredient['amount'] ?? '',
            ),
            $ingredients
        );
    }

    /**
     * @return CreateIngredient[]
     *
     * @phpstan-return non-empty-list<CreateIngredient>
     */
    public function toDomainCommands(Uuid $foodId): array
    {
        return array_map(
            fn (CreateFoodIngredientRequest $request) => $request->toDomainCommand($foodId),
            $this->ingredients,
        );
    }
}
