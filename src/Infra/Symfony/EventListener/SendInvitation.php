<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Symfony\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;
use Veliu\RateManu\Domain\Notification\Command\SendInvitationNotification;
use Veliu\RateManu\Domain\User\Event\UserInvited;

#[AsEventListener]
final readonly class SendInvitation
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(UserInvited $event): void
    {
        $this->messageBus->dispatch(new SendInvitationNotification($event->invitationTo, $event->invitationFrom));
    }
}
