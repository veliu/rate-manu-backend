<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User\Exception;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Exception\NotFoundException;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;

final class UserNotFoundException extends NotFoundException
{
    public static function byUuid(Uuid $uuid): self
    {
        return new self(sprintf('User with uuid "%s" not found.', $uuid->toString()));
    }

    public static function byEmail(EmailAddress $email): self
    {
        return new self(sprintf('User with email "%s" not found.', $email->value));
    }
}
