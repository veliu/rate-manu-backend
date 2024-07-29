<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User;

enum Status: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PENDING_REGISTRATION = 'pending_registration';
}
