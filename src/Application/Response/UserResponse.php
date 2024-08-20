<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Group\Group;
use Veliu\RateManu\Domain\User\Role;
use Veliu\RateManu\Domain\User\Status;
use Veliu\RateManu\Domain\User\User as UserEntity;

final readonly class UserResponse
{
    /**
     * @phpstan-param non-empty-string $email
     * @phpstan-param list<Role> $roles
     * @phpstan-param list<Uuid> $groups
     *
     * @param Role[] $roles
     * @param Uuid[] $groups
     */
    public function __construct(
        public Uuid $uuid,
        #[OA\Property(format: 'email')]
        public string $email,
        public Status $status,
        public array $roles,
        public array $groups,
    ) {
    }

    public static function fromEntity(UserEntity $entity): self
    {
        $groupIds = array_values(array_map(
            static fn (Group $group) => $group->id,
            $entity->getGroups()->toArray()
        ));

        return new self($entity->id, $entity->email->value, $entity->getStatus(), $entity->getRoleList(), $groupIds);
    }
}
