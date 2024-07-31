<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Rating;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Exception\NotFoundException;

interface RatingRepositoryInterface
{
    /** @throws NotFoundException */
    public function get(Uuid $uuid): Rating;

    public function save(Rating $rating): void;
}
