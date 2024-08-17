<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food\Command;

use Symfony\Component\Uid\Uuid;

final readonly class DeleteFood
{
    public function __construct(
        public Uuid $id
    ) {
    }
}
