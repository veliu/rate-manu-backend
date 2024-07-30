<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use Veliu\RateManu\Domain\User\User as UserEntity;

use function Psl\Type\non_empty_string;
use function Psl\Type\vec;

final readonly class User
{
    /**
     * @phpstan-param non-empty-string $uuid
     * @phpstan-param non-empty-string $email
     * @phpstan-param non-empty-string $status
     * @phpstan-param list<non-empty-string> $roles
     * @phpstan-param list<non-empty-string> $groups
     */
    public function __construct(
        public string $uuid,
        public string $email,
        public string $status,
        public array $roles,
        public array $groups,
    ) {
    }

    public static function fromEntity(UserEntity $entity): self
    {
        $id = non_empty_string()->coerce($entity->id->toString());

        $groupIds = vec(non_empty_string())->coerce($entity->getGroups()->getKeys());

        return new self($id, $entity->email->value, $entity->getStatus()->value, $entity->getRoles(), $groupIds);
    }
}
