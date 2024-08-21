<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Symfony\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;
use Veliu\RateManu\Domain\Notification\Command\SendGroupInvitationNotification;
use Veliu\RateManu\Domain\Notification\Command\SendRegistrationConfirmationNotification;
use Veliu\RateManu\Domain\User\Event\UserInvitedToGroup;
use Veliu\RateManu\Domain\User\Event\UserRegistered;

final readonly class NotificationListener
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    #[AsEventListener]
    public function sendGroupInvitationNotification(UserInvitedToGroup $event): void
    {
        $this->messageBus->dispatch(new SendGroupInvitationNotification($event->invitationTo, $event->invitationFrom, $event->group));
    }

    #[AsEventListener]
    public function sendRegistrationConfirmationNotification(UserRegistered $event): void
    {
        $this->messageBus->dispatch(new SendRegistrationConfirmationNotification($event->uuid));
    }
}
