<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Ingredient;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Exception\NotFoundException;
use Veliu\RateManu\Domain\SearchCriteria;

interface IngredientRepositoryInterface
{
    /** @throws NotFoundException */
    public function get(Uuid $uuid): Ingredient;

    /** @throws NotFoundException */
    public function delete(Ingredient $food): void;

    public function upsert(Ingredient $food): void;

    public function search(SearchCriteria $searchCriteria): IngredientEntityCollection;
}
