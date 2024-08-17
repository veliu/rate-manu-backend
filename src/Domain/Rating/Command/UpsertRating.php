<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Rating\Command;

use Symfony\Component\Uid\Uuid;

final readonly class UpsertRating
{
    public function __construct(
        public Uuid $userId,
        public Uuid $foodId,
        public int $rating,
    ) {
    }
}
