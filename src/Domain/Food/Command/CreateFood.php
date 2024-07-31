<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food\Command;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Group\Group;
use Veliu\RateManu\Domain\User\User;

final readonly class CreateFood
{
    /**
     * @phpstan-param non-empty-string $name
     * @phpstan-param non-empty-string|null $description
     */
    public function __construct(
        public Uuid $uuid,
        public string $name,
        public ?string $description,
        public Group $group,
        public User $user,
    ) {
    }
}
