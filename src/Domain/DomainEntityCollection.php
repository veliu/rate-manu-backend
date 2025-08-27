<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain;

/**
 * @template T of EntityInterface
 */
readonly class DomainEntityCollection
{
    /**
     * @param iterable<string, T> $items
     */
    protected iterable $items;

    protected int $count;

    /** @param iterable<string, T> $items */
    public function __construct(array $items)
    {
        $this->count = count($items);

        $processedItems = [];

        foreach ($items as $item) {
            $processedItems[$item->getId()->toString()] = $item;
        }

        $this->items = $processedItems;
    }

    /** @return \Traversable<string, T> */
    public function getIterator(): \Traversable
    {
        yield from $this->items;
    }

    public function count(): int
    {
        return $this->count;
    }
}
