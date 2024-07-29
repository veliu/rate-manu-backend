<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User;

enum Role: string
{
    case MEMBER = 'member';
    case OWNER = 'owner';
}
