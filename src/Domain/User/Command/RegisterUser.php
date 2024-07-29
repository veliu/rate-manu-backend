<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User\Command;

use Veliu\RateManu\Domain\ValueObject\Email;

final readonly class RegisterUser
{
    /** @psalm-param non-empty-string|null $password */
    public function __construct(
        public Email $email,
        public ?string $password,
    ) {
    }
}
