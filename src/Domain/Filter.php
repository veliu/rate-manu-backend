<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain;

final readonly class Filter
{
    /**
     * @phpstan-param non-empty-string $propertyName
     * @phpstan-param non-empty-string|non-empty-list<'asc'|'desc'> $propertyValue
     */
    public function __construct(
        public string $propertyName,
        public string|array $propertyValue,
    ) {
    }
}
