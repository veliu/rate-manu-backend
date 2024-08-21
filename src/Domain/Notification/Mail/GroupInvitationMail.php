<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Notification\Mail;

use Symfony\Component\Mime\Email;
use Veliu\RateManu\Domain\Group\Group;
use Veliu\RateManu\Domain\User\User;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;

final readonly class GroupInvitationMail
{
    private function __construct()
    {
    }

    public static function create(EmailAddress $to, User $invitedByUser, Group $group): Email
    {
        return (new Email())
        ->from('noreply@veliu.net')
        ->to($to->value)
        ->subject('You have been invited to a new group!')
        ->text(sprintf('Hello %s, %s invited you to the group %s. Check out now: %s', $to, $invitedByUser->email, $group->name, 'https://ratemanu.com/food'));
    }
}
