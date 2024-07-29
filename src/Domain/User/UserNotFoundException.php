<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Exception\NotFoundException;

final class UserNotFoundException extends NotFoundException
{
    public static function byUuid(Uuid $uuid): self
    {
        return new self(sprintf('User with uuid "%s" not found.', $uuid->toString()));
    }
}
