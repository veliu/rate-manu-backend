<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Comment\Command;

use Symfony\Component\Uid\Uuid;

final readonly class CreateComment
{
    /** @phpstan-param non-empty-string $comment */
    public function __construct(
        public Uuid $id,
        public Uuid $foodId,
        public Uuid $userId,
        public string $comment,
    ) {
    }
}
