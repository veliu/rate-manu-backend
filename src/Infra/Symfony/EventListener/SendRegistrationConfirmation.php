<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Symfony\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mailer\MailerInterface;
use Veliu\RateManu\Domain\User\Event\UserRegistered;
use Veliu\RateManu\Domain\UserRepositoryInterface;

#[AsEventListener]
final readonly class SendRegistrationConfirmation
{
    public function __construct(
        private MailerInterface $mailer,
        private UserRepositoryInterface $userRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(UserRegistered $event): void
    {
        $user = $this->userRepository->get($event->uuid);

        $this->logger->debug('sending email...');
    }
}
