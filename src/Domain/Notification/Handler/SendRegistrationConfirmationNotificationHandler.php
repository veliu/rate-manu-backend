<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Notification\Handler;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Veliu\RateManu\Domain\Exception\MailNotSend;
use Veliu\RateManu\Domain\Notification\Command\SendRegistrationConfirmationNotification;
use Veliu\RateManu\Domain\Notification\Mail\RegistrationConfirmation;
use Veliu\RateManu\Domain\User\Status;
use Veliu\RateManu\Domain\User\UserRepositoryInterface;

#[AsMessageHandler]
final readonly class SendRegistrationConfirmationNotificationHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private MailerInterface $mailer,
        private JWTEncoderInterface $encoder,
    ) {
    }

    public function __invoke(SendRegistrationConfirmationNotification $command): void
    {
        $user = $this->userRepository->get($command->userId);

        if (Status::PENDING_REGISTRATION !== $user->getStatus()) {
            return;
        }

        $token = $this->encoder->encode([
            'username' => $user->email->value,
        ]);

        $email = RegistrationConfirmation::create($user->email, $token);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new MailNotSend('Could not send registration confirmation mail!', $e);
        }
    }
}
