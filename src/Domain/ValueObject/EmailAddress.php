<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\ValueObject;

use Veliu\RateManu\Domain\DataBaseType;

final readonly class EmailAddress implements DataBaseType
{
    public const string REGEX = '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$';
    public const string DATABASE_TYPE_NAME = 'email';

    /**
     * @psalm-param non-empty-string $value
     */
    public function __construct(
        public string $value
    ) {
    }

    public static function getDatabaseTypeName(): string
    {
        return self::DATABASE_TYPE_NAME;
    }
}
