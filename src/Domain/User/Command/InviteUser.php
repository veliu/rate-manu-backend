<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User\Command;

use Veliu\RateManu\Domain\ValueObject\EmailAddress;

final readonly class InviteUser
{
    public function __construct(
        public EmailAddress $invitationTo,
        public EmailAddress $invitationFrom,
    ) {
    }
}
