<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Rating\Rating as Entity;

use function Psl\Type\non_empty_string;

final readonly class PersonalRatingResponse
{
    public function __construct(
        public Uuid $id,
        public Uuid $food,
        #[OA\Property(type: 'int', enum: [1, 2, 3, 4, 5, 6])]
        public int $rating,
        public Uuid $createdBy,
        #[OA\Property(format: 'date-time')]
        public string $createdAt,
        #[OA\Property(format: 'date-time')]
        public string $updatedAt,
    ) {
    }

    public static function fromEntity(Entity $entity): self
    {
        return new self(
            $entity->id,
            $entity->food->id,
            $entity->getRating(),
            $entity->createdBy->id,
            non_empty_string()->coerce($entity->getCreatedAt()?->format(\DateTime::ATOM)),
            non_empty_string()->coerce($entity->getUpdatedAt()?->format(\DateTime::ATOM)),
        );
    }
}
