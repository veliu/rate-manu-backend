<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Rating\Command;

use Symfony\Component\Uid\Uuid;

final readonly class UpdateRating
{
    public function __construct(
        public Uuid $id,
        public Uuid $userId,
        public int $rating,
    ) {
    }
}
