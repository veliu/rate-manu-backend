<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain;

final readonly class Filter
{
    /**
     * @phpstan-param non-empty-string $propertyName
     */
    public function __construct(
        public string $entity,
        public string $propertyName,
        public FilterOperator $operator,
        public string|int|bool $value,
    ) {
    }
}
