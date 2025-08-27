<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Ingredient;

use Veliu\RateManu\Domain\DomainEntityCollection;

/**
 * @implements \IteratorAggregate<string, Ingredient>
 *
 * @extends DomainEntityCollection<string, Ingredient>
 */
final readonly class IngredientEntityCollection extends DomainEntityCollection implements \IteratorAggregate
{
}
