<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\User\User;

interface UserRepositoryInterface
{
    public function create(User $user): void;

    public function get(Uuid $uuid): User;
}
