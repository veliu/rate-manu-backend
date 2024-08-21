<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Symfony\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;
use Veliu\RateManu\Domain\Notification\Command\SendGroupInvitationNotification;
use Veliu\RateManu\Domain\User\Event\UserInvitedToGroup;

final readonly class NotificationListener
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    #[AsEventListener]
    public function dispatchGroupInvitationNotification(UserInvitedToGroup $event): void
    {
        $this->messageBus->dispatch(new SendGroupInvitationNotification($event->invitationTo, $event->invitationFrom, $event->group));
    }
}
