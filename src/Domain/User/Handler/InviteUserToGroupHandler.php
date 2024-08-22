<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Veliu\RateManu\Domain\Group\GroupRepositoryInterface;
use Veliu\RateManu\Domain\User\Command\InviteUserToGroup;
use Veliu\RateManu\Domain\User\Event\UserInvitedToGroup;
use Veliu\RateManu\Domain\User\Exception\UserNotFoundException;
use Veliu\RateManu\Domain\User\Role;
use Veliu\RateManu\Domain\User\User;
use Veliu\RateManu\Domain\User\UserRepositoryInterface;

#[AsMessageHandler]
final readonly class InviteUserToGroupHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private GroupRepositoryInterface $groupRepository,
        private EventDispatcherInterface $eventDispatcher,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(InviteUserToGroup $command): void
    {
        try {
            $invitationToUser = $this->userRepository->getByEmail($command->invitationTo);
        } catch (UserNotFoundException) {
            $invitationToUser = new User(
                id: Uuid::v4(),
                email: $command->invitationTo,
                roles: ['user']
            );
        }
        $invitationFromUser = $this->userRepository->get($command->invitedBy);

        $group = $this->groupRepository->get($command->group);

        $invitationToUser->createGroupRelation($group, Role::MEMBER);

        $this->entityManager->persist($invitationToUser);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(new UserInvitedToGroup($invitationToUser->id, $invitationFromUser->id, $command->group));
    }
}
