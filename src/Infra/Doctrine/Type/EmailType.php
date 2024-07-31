<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;

final class EmailType extends Type
{
    public function convertToPHPValue($value, AbstractPlatform $platform): EmailAddress
    {
        return EmailAddress::fromAny($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if ($value instanceof EmailAddress) {
            return $value->value;
        }

        if (is_string($value)) {
            return $value;
        }

        throw new \LogicException('Expected string or email type');
    }

    #[\Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL(['length' => 320, 'unique' => true]);
    }

    #[\Override]
    public function getName(): string
    {
        return EmailAddress::getDatabaseTypeName();
    }
}
