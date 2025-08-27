<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Veliu\RateManu\Domain\Ingredient\IngredientEntityCollection;

final readonly class IngredientCollectionResponse
{
    /**
     * @phpstan-param list<FoodResponse> $items
     */
    private function __construct(
        #[OA\Property(type: 'integer')]
        public int $count,
        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: IngredientResponse::class)))]
        public array $items,
    ) {
    }

    public static function fromDomainCollection(IngredientEntityCollection $collection): self
    {
        $foodEntries = [];
        foreach ($collection as $item) {
            $foodEntries[] = IngredientResponse::fromEntity($item);
        }

        return new self($collection->count(), $foodEntries);
    }
}
