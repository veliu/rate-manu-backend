<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User\Event;

use Symfony\Component\Uid\Uuid;

final readonly class UserRegistered
{
    public function __construct(public Uuid $uuid)
    {
    }
}
