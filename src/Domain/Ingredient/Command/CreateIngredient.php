<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Ingredient\Command;

use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Group\Group;
use Veliu\RateManu\Domain\Ingredient\UnitEnum;
use Veliu\RateManu\Domain\User\User;

#[AsMessage]
final readonly class CreateIngredient
{
    /** @phpstan-param non-empty-string $name */
    public function __construct(
        public Uuid $id,
        public string $name,
        public UnitEnum $defaultUnit,
        public User $user,
        public ?Group $group,
    ) {
    }
}
