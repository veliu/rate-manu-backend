<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Notification\Handler;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Veliu\RateManu\Domain\Exception\MailNotSend;
use Veliu\RateManu\Domain\Group\GroupRepositoryInterface;
use Veliu\RateManu\Domain\Notification\Command\SendGroupInvitationNotification;
use Veliu\RateManu\Domain\Notification\Mail\GroupInvitationMail;
use Veliu\RateManu\Domain\User\Status;
use Veliu\RateManu\Domain\User\UserRepositoryInterface;

#[AsMessageHandler]
final readonly class SendGroupInvitationNotificationHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private GroupRepositoryInterface $groupRepository,
        private MailerInterface $mailer,
    ) {
    }

    public function __invoke(SendGroupInvitationNotification $command): void
    {
        $invitedByUser = $this->userRepository->get($command->invitationFrom);
        $invitedToUser = $this->userRepository->get($command->invitationTo);
        $group = $this->groupRepository->get($command->group);

        if (Status::PENDING_REGISTRATION !== $invitedToUser->getStatus()) {
            return;
        }

        $email = GroupInvitationMail::create($invitedToUser->email, $invitedByUser, $group);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new MailNotSend('Could not send invitation mail!', $e);
        }
    }
}
