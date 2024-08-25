<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User\Command;

use Symfony\Component\Uid\Uuid;

final readonly class UpdateUser
{
    /** @psalm-param non-empty-string $name */
    public function __construct(
        public Uuid $id,
        public string $name,
    ) {
    }
}
