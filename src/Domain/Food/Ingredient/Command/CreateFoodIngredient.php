<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food\Ingredient\Command;

use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Uid\Uuid;

#[AsMessage]
final readonly class CreateFoodIngredient
{
    public function __construct(
        public Uuid $id,
        public Uuid $foodId,
        public Uuid $ingredientId,
        public float $amount,
        public ?string $unit = null,
    ) {
    }
}
