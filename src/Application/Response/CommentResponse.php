<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Comment\Comment as Entity;

use function Psl\Type\non_empty_string;

final readonly class CommentResponse
{
    /**
     * @phpstan-param non-empty-string $comment
     * @phpstan-param non-empty-string $createdAt
     * @phpstan-param non-empty-string $updatedAt
     */
    public function __construct(
        public Uuid $id,
        public Uuid $author,
        #[OA\Property(format: 'date-time')]
        public string $createdAt,
        #[OA\Property(format: 'date-time')]
        public string $updatedAt,
        public string $comment,
    ) {
    }

    public static function fromEntity(Entity $entity): self
    {
        return new self(
            $entity->id,
            $entity->author->id,
            non_empty_string()->coerce($entity->getCreatedAt()?->format(\DateTime::ATOM)),
            non_empty_string()->coerce($entity->getUpdatedAt()?->format(\DateTime::ATOM)),
            $entity->getComment(),
        );
    }
}
