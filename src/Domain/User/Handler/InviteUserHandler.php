<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User\Handler;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Veliu\RateManu\Domain\Group\Group;
use Veliu\RateManu\Domain\User\Command\InviteUser;
use Veliu\RateManu\Domain\User\Event\UserInvited;
use Veliu\RateManu\Domain\User\Exception\UserNotFoundException;
use Veliu\RateManu\Domain\User\Role;
use Veliu\RateManu\Domain\User\User;
use Veliu\RateManu\Domain\UserRepositoryInterface;

use function Psl\Type\instance_of;

#[AsMessageHandler]
final readonly class InviteUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(InviteUser $command): void
    {
        try {
            $invitationToUser = $this->userRepository->getByEmail($command->invitationTo);
        } catch (UserNotFoundException) {
            $invitationToUser = new User(
                id: Uuid::v4(),
                email: $command->invitationTo,
                roles: [Role::MEMBER]
            );
        }
        $invitationFromUser = $this->userRepository->getByEmail($command->invitationFrom);

        $firstGroup = instance_of(Group::class)->coerce($invitationFromUser->getGroups()->first());

        $firstGroup->addMember($invitationToUser);

        $this->userRepository->create($invitationToUser);

        $this->eventDispatcher->dispatch(new UserInvited($invitationToUser->id, $invitationFromUser->id));
    }
}