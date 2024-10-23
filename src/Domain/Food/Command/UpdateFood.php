<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food\Command;

use Symfony\Component\Uid\Uuid;

final readonly class UpdateFood
{
    /**
     * @phpstan-param non-empty-string $name
     * @phpstan-param non-empty-string|null $description
     */
    public function __construct(
        public Uuid $id,
        public string $name,
        public ?string $description,
    ) {
    }
}
