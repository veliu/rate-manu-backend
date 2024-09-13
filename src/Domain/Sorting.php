<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain;

final readonly class Sorting
{
    /**
     * @phpstan-param non-empty-string $propertyName
     * @phpstan-param 'asc'|'desc' $direction
     */
    public function __construct(
        public string $propertyName,
        public string $direction,
    ) {
    }
}
