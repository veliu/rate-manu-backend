<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Rating;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Exception\NotFoundException;
use Veliu\RateManu\Domain\Food\Food;
use Veliu\RateManu\Domain\User\User;

interface RatingRepositoryInterface
{
    /** @throws NotFoundException */
    public function get(Uuid $uuid): Rating;

    /** @throws NotFoundException */
    public function getByUserAndFood(User $user, Food $food): Rating;

    public function findForAllMembers(User $user, Food $food): RatingCollection;

    public function save(Rating $rating): void;
}
