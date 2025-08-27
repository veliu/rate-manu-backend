<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Ingredient\Event;

use Symfony\Component\Uid\Uuid;

final readonly class IngredientCreated
{
    public function __construct(public Uuid $id)
    {
    }
}
