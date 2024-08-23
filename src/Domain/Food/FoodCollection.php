<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food;

/** @implements \IteratorAggregate<string, Food> */
final readonly class FoodCollection implements \IteratorAggregate
{
    /** @param array<string, Food> $items */
    public function __construct(
        private iterable $items = []
    ) {
    }

    /** @phpstan-return \Generator<string, Food> */
    public function getIterator(): \Generator
    {
        yield from $this->items;
    }
}
