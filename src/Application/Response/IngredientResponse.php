<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Ingredient\Ingredient as Entity;
use Veliu\RateManu\Domain\Ingredient\UnitEnum;

final readonly class IngredientResponse
{
    /**
     * @phpstan-param non-empty-string $name
     * @phpstan-param non-empty-string $defaultUnit
     */
    public function __construct(
        public Uuid $id,
        public string $name,
        #[OA\Property(enum: UnitEnum::VALUES)]
        public string $defaultUnit,
    ) {
    }

    public static function fromEntity(Entity $entity): self
    {
        return new self(
            $entity->id,
            $entity->name,
            $entity->defaultUnit->value,
        );
    }
}
