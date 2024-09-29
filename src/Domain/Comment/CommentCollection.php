<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Comment;

/** @implements \IteratorAggregate<string, Comment> */
final readonly class CommentCollection implements \IteratorAggregate
{
    /** @param array<string, Comment> $items */
    public function __construct(
        private iterable $items = [],
        public int $total = 0,
    ) {
    }

    /** @phpstan-return \Generator<string, Comment> */
    public function getIterator(): \Generator
    {
        yield from $this->items;
    }
}
