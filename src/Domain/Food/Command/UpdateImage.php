<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food\Command;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

final readonly class UpdateImage
{
    public function __construct(
        public Uuid $foodId,
        public UploadedFile $image
    ) {
    }
}
