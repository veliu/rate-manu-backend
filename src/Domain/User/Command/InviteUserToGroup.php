<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User\Command;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;

final readonly class InviteUserToGroup
{
    public function __construct(
        public EmailAddress $invitationTo,
        public Uuid $invitedBy,
        public Uuid $group,
    ) {
    }
}
