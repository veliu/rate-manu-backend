<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Notification\Handler;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Veliu\RateManu\Domain\Exception\MailNotSend;
use Veliu\RateManu\Domain\Notification\Command\SendInvitationNotification;
use Veliu\RateManu\Domain\Notification\Mail\Invitation;
use Veliu\RateManu\Domain\User\Status;
use Veliu\RateManu\Domain\UserRepositoryInterface;

#[AsMessageHandler]
final readonly class SendInvitationNotificationHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private MailerInterface $mailer,
        private JWTTokenManagerInterface $tokenManager,
    ) {
    }

    public function __invoke(SendInvitationNotification $command): void
    {
        $invitedByUser = $this->userRepository->get($command->invitationFrom);
        $invitedToUser = $this->userRepository->get($command->invitationTo);

        if (Status::PENDING_REGISTRATION !== $invitedToUser->getStatus()) {
            return;
        }

        $token = $this->tokenManager->create($invitedToUser);

        $email = Invitation::create($invitedToUser->email, $invitedByUser, $token);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new MailNotSend('Could not send invitation mail!', $e);
        }
    }
}
