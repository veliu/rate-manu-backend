<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Symfony\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;
use Veliu\RateManu\Domain\Notification\Command\SendRegistrationConfirmationNotification;
use Veliu\RateManu\Domain\User\Event\UserRegistered;

#[AsEventListener]
final readonly class SendRegistrationConfirmation
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(UserRegistered $event): void
    {
        $this->messageBus->dispatch(new SendRegistrationConfirmationNotification($event->uuid));
    }
}
