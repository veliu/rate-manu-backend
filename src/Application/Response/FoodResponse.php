<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Food\Food as Entity;

use function Psl\Type\non_empty_string;

#[OA\Components]
final readonly class FoodResponse
{
    public function __construct(
        #[OA\Property(type: 'string', format: 'uuid')]
        public Uuid $id,
        #[OA\Property(type: 'string')]
        public string $name,
        #[OA\Property(type: 'string', nullable: true)]
        public ?string $description,
        #[OA\Property(type: 'string', format: 'uuid')]
        public Uuid $author,
        #[OA\Property(type: 'string', format: 'uuid')]
        public Uuid $group,
        #[OA\Property(type: 'string', format: 'date-time')]
        public string $createdAt,
        #[OA\Property(type: 'string', format: 'date-time')]
        public string $updatedAt,
        #[OA\Property(type: 'string', format: 'url', nullable: true)]
        public ?string $image,
        #[OA\Property(type: 'int', enum: [1, 2, 3, 4, 5, 6])]
        public int $averageRating,
    ) {
    }

    public static function fromEntity(Entity $entity, string $domain = 'https://api.ratemanu.com/'): self
    {
        return new self(
            $entity->id,
            $entity->name,
            $entity->description,
            $entity->author->id,
            $entity->group->getId(),
            non_empty_string()->coerce($entity->getCreatedAt()?->format(\DateTime::ATOM)),
            non_empty_string()->coerce($entity->getUpdatedAt()?->format(\DateTime::ATOM)),
            $domain.$entity->getImage(),
            $entity->getAverageRating(),
        );
    }
}
