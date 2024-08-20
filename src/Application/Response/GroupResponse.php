<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Group\Group as GroupEntity;
use Veliu\RateManu\Domain\User\User as UserEntity;

final readonly class GroupResponse
{
    /**
     * @param UserResponse[] $members
     */
    public function __construct(
        public Uuid $id,
        public string $name,
        public array $members,
    ) {
    }

    public static function fromEntity(GroupEntity $entity): self
    {
        $members = array_map(static fn (UserEntity $user) => UserResponse::fromEntity($user), $entity->getUsers()->toArray());

        return new self($entity->id, $entity->name, $members);
    }
}
