<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food;

/** @phpstan-implements \IteratorAggregate<Food> */
final readonly class FoodCollection implements \IteratorAggregate
{
    /** @param array<string, Food> $items */
    public function __construct(
        private array $items = []
    ) {
    }

    /** @phpstan-return \Generator<string, Food> */
    public function getIterator(): \Generator
    {
        yield from $this->items;
    }
}
