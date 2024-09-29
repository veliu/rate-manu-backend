<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Rating;

/** @implements \IteratorAggregate<string, Rating> */
final readonly class RatingCollection implements \IteratorAggregate
{
    /** @param array<string, Rating> $items */
    public function __construct(
        private iterable $items = [],
    ) {
    }

    /** @phpstan-return \Generator<string, Rating> */
    public function getIterator(): \Generator
    {
        yield from $this->items;
    }
}
