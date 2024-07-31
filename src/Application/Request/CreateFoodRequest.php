<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Food\Command\CreateFood;
use Veliu\RateManu\Domain\Group\Group;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\non_empty_string;
use function Psl\Type\nullable;

final readonly class CreateFoodRequest
{
    public function __construct(
        public mixed $id,
        public mixed $name,
        public mixed $description,
    ) {
    }

    public function toDomainCommand(Group $group, User $user): CreateFood
    {
        $id = non_empty_string()->matches($this->id)
            ? Uuid::fromString($this->id)
            : Uuid::v4();

        $name = non_empty_string()->coerce($this->name);
        $description = nullable(non_empty_string())->coerce($this->description);

        return new CreateFood($id, $name, $description, $group, $user);
    }
}
