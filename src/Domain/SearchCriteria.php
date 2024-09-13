<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain;

use Symfony\Component\Uid\Uuid;

final readonly class SearchCriteria
{
    /**
     * @param Sorting[] $sorting,
     * @param Filter[]  $filter,
     *
     * @phpstan-param list<Sorting> $sorting
     * @phpstan-param list<Filter> $filter
     * @phpstan-param positive-int|0 $offset
     * @phpstan-param positive-int $limit
     */
    public function __construct(
        public Uuid $userId,
        public array $sorting = [],
        public array $filter = [],
        public int $offset = 0,
        public int $limit = 10,
    ) {
    }
}
