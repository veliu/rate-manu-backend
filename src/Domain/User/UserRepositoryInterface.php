<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\User\Exception\UserNotCreatedException;
use Veliu\RateManu\Domain\User\Exception\UserNotFoundException;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;

interface UserRepositoryInterface
{
    /** @throws UserNotCreatedException */
    public function create(User $user): void;

    /** @throws UserNotFoundException */
    public function get(Uuid $uuid): User;

    /** @throws UserNotFoundException */
    public function getByEmail(EmailAddress $email): User;
}
