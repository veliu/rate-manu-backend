<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User\Event;

use Symfony\Component\Uid\Uuid;

final readonly class UserInvited
{
    public function __construct(public Uuid $invitationTo, public Uuid $invitationFrom)
    {
    }
}
