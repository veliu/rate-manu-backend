<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\User\Status;
use Veliu\RateManu\Domain\User\User as UserEntity;

final readonly class GroupMemberResponse
{
    /**
     * @phpstan-param non-empty-string $email
     */
    public function __construct(
        public Uuid $id,
        #[OA\Property(format: 'email')]
        public string $email,
        public Status $status,
    ) {
    }

    public static function fromEntity(UserEntity $entity): self
    {
        return new self($entity->id, $entity->email->value, $entity->getStatus());
    }
}
