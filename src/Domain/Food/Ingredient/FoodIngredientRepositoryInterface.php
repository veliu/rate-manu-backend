<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food\Ingredient;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Exception\NotFoundException;

interface FoodIngredientRepositoryInterface
{
    /** @throws NotFoundException */
    public function get(Uuid $uuid): FoodIngredient;

    /** @throws NotFoundException */
    public function delete(FoodIngredient $food): void;

    public function upsert(FoodIngredient $food): void;

    public function findByFood(Uuid $foodId): FoodIngredientEntityCollection;

    public function findByFoodAndIngredient(Uuid $foodId, Uuid $ingredientId): ?FoodIngredient;
}
