<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\User\GroupRelation;
use Veliu\RateManu\Domain\User\Status;
use Veliu\RateManu\Domain\User\User as UserEntity;

use function Psl\Type\instance_of;
use function Psl\Type\non_empty_vec;

final readonly class UserResponse
{
    /**
     * @phpstan-param non-empty-string $email
     * @phpstan-param non-empty-list<Uuid> $groups
     * @phpstan-param non-empty-string|null $name
     *
     * @param Uuid[] $groups
     */
    public function __construct(
        public Uuid $id,
        #[OA\Property(format: 'email')]
        public string $email,
        public Status $status,
        public array $groups,
        public ?string $name,
    ) {
    }

    public static function fromEntity(UserEntity $entity): self
    {
        $groupIds = array_values(array_map(
            static fn (GroupRelation $group) => $group->group->getId(),
            $entity->getGroupRelations()->toArray()
        ));

        $groupIds = non_empty_vec(instance_of(Uuid::class))->coerce($groupIds);

        return new self($entity->id, $entity->email->value, $entity->getStatus(), $groupIds, $entity->name);
    }
}
