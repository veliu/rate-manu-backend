<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Group;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Exception\NotFoundException;

interface GroupRepositoryInterface
{
    /** @throws NotFoundException */
    public function get(Uuid $uuid): Group;
}
