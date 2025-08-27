<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food\Ingredient;

use Veliu\RateManu\Domain\DomainEntityCollection;

/**
 * @implements \IteratorAggregate<string, FoodIngredient>
 *
 * @extends DomainEntityCollection<string, FoodIngredient>
 */
final readonly class FoodIngredientEntityCollection extends DomainEntityCollection implements \IteratorAggregate
{
}
