<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Exception\NotFoundException;

interface FoodRepositoryInterface
{
    /** @throws NotFoundException */
    public function get(Uuid $uuid): Food;

    public function create(Food $food): void;
}
