<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Food\Food as Entity;
use Veliu\RateManu\Domain\Rating\Rating;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\non_empty_string;

final readonly class FoodResponse
{
    /**
     * @param Uuid[] $ratings
     *
     * @phpstan-param non-empty-string $name
     * @phpstan-param non-empty-string|null $description
     * @phpstan-param non-empty-string $createdAt
     * @phpstan-param non-empty-string $updatedAt
     * @phpstan-param non-empty-string|null $image
     * @phpstan-param int<0,6> $averageRating
     * @phpstan-param list<Uuid> $ratings
     */
    public function __construct(
        public Uuid $id,
        public string $name,
        public ?string $description,
        public Uuid $author,
        public Uuid $group,
        #[OA\Property(format: 'date-time')]
        public string $createdAt,
        #[OA\Property(format: 'date-time')]
        public string $updatedAt,
        #[OA\Property(
            format: 'url',
            example: 'https://api.ratemanu.com/uploads/food/123-image.jpeg'
        )]
        public ?string $image,
        #[OA\Property(type: 'int', enum: [0, 1, 2, 3, 4, 5, 6])]
        public int $averageRating,
        public array $ratings,
        public ?RatingResponse $personalRating = null,
    ) {
    }

    public static function fromEntity(Entity $entity, User $user, string $domain = 'https://api.ratemanu.com/'): self
    {
        $personalRating = $entity->getRatingForUser($user);

        return new self(
            $entity->id,
            $entity->name,
            $entity->description,
            $entity->author->id,
            $entity->group->getId(),
            non_empty_string()->coerce($entity->getCreatedAt()?->format(\DateTime::ATOM)),
            non_empty_string()->coerce($entity->getUpdatedAt()?->format(\DateTime::ATOM)),
            $entity->getImage() ? $domain.$entity->getImage() : null,
            $entity->getAverageRating(),
            array_filter(array_map(static fn (Rating $rating) => $rating->id, $entity->ratings->toArray())),
            $personalRating ? RatingResponse::fromEntity($personalRating) : null,
        );
    }
}
