<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food\Ingredient\Event;

use Symfony\Component\Uid\Uuid;

final readonly class FoodIngredientCreated
{
    public function __construct(
        public Uuid $id,
    ) {
    }
}
