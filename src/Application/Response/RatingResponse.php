<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Rating\Rating as Entity;

use function Psl\Type\non_empty_string;

final readonly class RatingResponse
{
    public function __construct(
        public Uuid $id,
        public Uuid $food,
        public int $rating,
        public Uuid $createdBy,
        public string $createdAt,
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
