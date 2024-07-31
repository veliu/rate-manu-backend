<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Rating\Command;

use Symfony\Component\Uid\Uuid;

final readonly class CreateRating
{
    public function __construct(
        public Uuid $id,
        public Uuid $userId,
        public Uuid $foodId,
        public int $rating,
    ) {
    }
}
