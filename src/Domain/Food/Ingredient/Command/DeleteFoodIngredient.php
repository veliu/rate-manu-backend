<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food\Ingredient\Command;

use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Uid\Uuid;

#[AsMessage]
final readonly class DeleteFoodIngredient
{
    public function __construct(
        public Uuid $foodId,
        public Uuid $ingredientId,
    ) {
    }
}
