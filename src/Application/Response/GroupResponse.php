<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\User\GroupRelation;
use Veliu\RateManu\Domain\User\Role;
use Veliu\RateManu\Domain\User\User as UserEntity;

final readonly class GroupResponse
{
    /**
     * @param UserResponse[] $members
     */
    public function __construct(
        public Uuid $id,
        public string $name,
        public Role $role,
        public array $members,
    ) {
    }

    public static function fromEntity(GroupRelation $entity): self
    {
        $members = array_map(
            static fn (UserEntity $user) => UserResponse::fromEntity($user),
            $entity->group->getUsers()->toArray()
        );

        return new self($entity->group->id, $entity->group->name, $entity->role, $members);
    }
}
