<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Notification\Command;

use Symfony\Component\Uid\Uuid;

final readonly class SendRegistrationConfirmationNotification
{
    public function __construct(
        public Uuid $userId,
    ) {
    }
}
