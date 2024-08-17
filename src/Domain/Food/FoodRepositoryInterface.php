<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Exception\NotFoundException;
use Veliu\RateManu\Domain\SearchCriteria;

interface FoodRepositoryInterface
{
    /** @throws NotFoundException */
    public function get(Uuid $uuid): Food;

    /** @throws NotFoundException */
    public function delete(Food $food): void;

    public function create(Food $food): void;

    public function search(SearchCriteria $searchCriteria): FoodCollection;
}
