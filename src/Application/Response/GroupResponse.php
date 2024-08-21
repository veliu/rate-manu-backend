<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\User\GroupRelation;
use Veliu\RateManu\Domain\User\Role;

final readonly class GroupResponse
{
    /**
     * @param GroupMemberResponse[] $members
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
        $userRelations = $entity->group->getUserRelations()->toArray();

        $members = array_map(
            static fn (GroupRelation $relation) => GroupMemberResponse::fromEntity($relation->user),
            $userRelations,
        );

        return new self($entity->group->getId(), $entity->group->name, $entity->role, $members);
    }
}
