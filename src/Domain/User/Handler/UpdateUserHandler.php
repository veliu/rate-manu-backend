<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Veliu\RateManu\Domain\User\Command\UpdateUser;
use Veliu\RateManu\Domain\User\UserRepositoryInterface;

#[AsMessageHandler]
final readonly class UpdateUserHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepositoryInterface $repository,
    ) {
    }

    public function __invoke(UpdateUser $command): void
    {
        $user = $this->repository->get($command->id);

        $user->name = $command->name;

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
