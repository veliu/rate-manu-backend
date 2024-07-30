<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User\Command;

final readonly class ConfirmUserRegistration
{
    public function __construct(
        public string $token,
    ) {
    }
}
