<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Veliu\RateManu\Domain\ValueObject\Email;

final class EmailType extends Type
{
    public function convertToPHPValue($value, AbstractPlatform $platform): Email
    {
        if (is_string($value) && '' !== $value) {
            return new Email($value);
        }

        throw new \LogicException('Expected string or email type');
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if ($value instanceof Email) {
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
        return Email::getDatabaseTypeName();
    }
}
