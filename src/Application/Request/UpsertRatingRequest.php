<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Veliu\RateManu\Domain\Rating\Command\UpsertRating;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\non_empty_string;
use function Psl\Type\positive_int;

final readonly class UpsertRatingRequest
{
    public function __construct(
        #[OA\Property(type: 'string', format: 'uuid')]
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public mixed $food,

        #[OA\Property(type: 'integer', maximum: 6, minimum: 1)]
        #[Assert\Type('int')]
        #[Assert\Range(min: 1, max: 6)]
        #[Assert\NotNull]
        public mixed $rating,
    ) {
    }

    public function toDomainCommand(User $user): UpsertRating
    {
        $foodId = Uuid::fromString(non_empty_string()->coerce($this->food));
        $rating = positive_int()->coerce($this->rating);

        return new UpsertRating($user->id, $foodId, $rating);
    }
}
