<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Veliu\RateManu\Domain\Group\Group;
use Veliu\RateManu\Domain\User\Command\RegisterUser;
use Veliu\RateManu\Domain\User\Event\UserRegistered;
use Veliu\RateManu\Domain\User\Role;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\non_empty_string;

#[AsMessageHandler]
final readonly class RegisterUserHandler
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(RegisterUser $command): void
    {
        $user = new User(Uuid::v4(), $command->email, null, [Role::OWNER]);

        $password = $command->password;

        if (non_empty_string()->matches($password)) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword(non_empty_string()->coerce($hashedPassword));
        }

        $group = new Group(Uuid::v4(), 'Veliu');
        $group->addMember($user);

        $this->entityManager->persist($user);
        $this->entityManager->persist($group);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new UserRegistered($user->id));
    }
}
