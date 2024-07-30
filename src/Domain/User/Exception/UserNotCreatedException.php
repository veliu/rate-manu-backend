<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User\Exception;

use Veliu\RateManu\Domain\Exception\NotFoundException;
use Veliu\RateManu\Domain\ValueObject\Email;

final class UserNotCreatedException extends NotFoundException
{
    public static function userWithEmailAlreadyExists(Email $email): self
    {
        return new self(sprintf('User with email "%s" already exists.', $email->value));
    }
}
