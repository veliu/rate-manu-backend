<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Rating\Command\CreateRating;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\non_empty_string;
use function Psl\Type\positive_int;

final readonly class CreateRatingRequest
{
    public function __construct(
        public mixed $id,
        public mixed $food,
        public mixed $rating
    ) {
    }

    public function toDomainCommand(User $user): CreateRating
    {
        $id = non_empty_string()->matches($this->id)
            ? Uuid::fromString($this->id)
            : Uuid::v4();

        $foodId = Uuid::fromString(non_empty_string()->coerce($this->food));
        $rating = positive_int()->coerce($this->rating);

        return new CreateRating($id, $user->id, $foodId, $rating);
    }
}
