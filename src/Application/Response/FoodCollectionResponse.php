<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use Veliu\RateManu\Domain\Food\FoodCollection;

final readonly class FoodCollectionResponse
{
    /**
     * @phpstan-param list<FoodResponse> $foods
     */
    private function __construct(
        public int $count,
        public array $foods,
    ) {
    }

    public static function fromDomainCollection(FoodCollection $collection): self
    {
        $count = 0;
        $foodEntries = [];
        foreach ($collection as $item) {
            $foodEntries[] = FoodResponse::fromEntity($item);
            ++$count;
        }

        return new self($count, $foodEntries);
    }
}
