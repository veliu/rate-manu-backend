<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Comment;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Exception\NotFoundException;

interface CommentRepositoryInterface
{
    /** @throws NotFoundException */
    public function get(Uuid $id): Comment;

    public function getForFood(Uuid $foodId): CommentCollection;
}
