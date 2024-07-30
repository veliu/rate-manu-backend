<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User\Exception;

use Veliu\RateManu\Domain\Exception\NotFoundException;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;

final class UserNotCreatedException extends NotFoundException
{
    public static function userWithEmailAlreadyExists(EmailAddress $email): self
    {
        return new self(sprintf('User with email "%s" already exists.', $email->value));
    }
}
