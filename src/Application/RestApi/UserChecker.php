<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Veliu\RateManu\Domain\User\Status;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\instance_of;

final readonly class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        $user = instance_of(User::class)->coerce($user);

        if (Status::ACTIVE !== $user->getStatus()) {
            throw new CustomUserMessageAccountStatusException('You need to confirm your registration first.');
        }

        if (null === $user->getPassword()) {
            throw new CustomUserMessageAccountStatusException('You need to set a password first.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        $user = instance_of(User::class)->coerce($user);

        if (Status::ACTIVE !== $user->getStatus()) {
            throw new CustomUserMessageAccountStatusException('You need to confirm your registration first.');
        }
    }
}
