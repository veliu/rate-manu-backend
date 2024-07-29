<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User\Handler;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Veliu\RateManu\Domain\User\Command\RegisterUser;
use Veliu\RateManu\Domain\User\Event\UserRegistered;
use Veliu\RateManu\Domain\User\Role;
use Veliu\RateManu\Domain\User\User;
use Veliu\RateManu\Domain\UserRepositoryInterface;

use function Psl\Type\non_empty_string;

#[AsMessageHandler]
final readonly class RegisterUserHandler
{
    public function __construct(
        private UserRepositoryInterface $users,
        private EventDispatcherInterface $eventDispatcher,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function __invoke(RegisterUser $command): void
    {
        $user = new User(Uuid::v4(), $command->email, null, [Role::OWNER->value]);

        $password = $command->password;

        if (non_empty_string()->matches($password)) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword(non_empty_string()->coerce($hashedPassword));
        }

        $this->users->create($user);
        $this->eventDispatcher->dispatch(new UserRegistered($user->id));
    }
}
