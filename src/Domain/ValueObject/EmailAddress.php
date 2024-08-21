<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\ValueObject;

use Psl\Type\Exception\CoercionException;
use Veliu\RateManu\Domain\DataBaseType;

use function Psl\Type\non_empty_string;

final readonly class EmailAddress implements DataBaseType
{
    public const string REGEX = '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$^';
    public const string DATABASE_TYPE_NAME = 'email';

    /**
     * @psalm-param non-empty-string $value
     */
    private function __construct(
        public string $value
    ) {
    }

    /** @throws \InvalidArgumentException */
    public static function fromAny(mixed $value): self
    {
        self::validate($value);

        return new self($value);
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @phpstan-assert non-empty-string $value
     */
    private static function validate(mixed $value): void
    {
        try {
            $stringValue = non_empty_string()->coerce($value);
        } catch (CoercionException) {
            throw new \InvalidArgumentException('Email address must be a string');
        }

        if (0 === preg_match(self::REGEX, $stringValue)) {
            throw new \InvalidArgumentException(sprintf('Value "%s" is not an valid email address', $stringValue));
        }
    }

    public static function getDatabaseTypeName(): string
    {
        return self::DATABASE_TYPE_NAME;
    }

    /** @phpstan-return non-empty-string */
    public function __toString(): string
    {
        return $this->value;
    }
}
