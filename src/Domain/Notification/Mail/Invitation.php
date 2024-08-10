<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Notification\Mail;

use Symfony\Component\Mime\Email;
use Veliu\RateManu\Domain\User\User;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;

final readonly class Invitation
{
    private function __construct()
    {
    }

    public static function create(EmailAddress $to, User $invitedByUser, string $token): Email
    {
        /* @todo Send different link. We need a finish registration endpoint instead of confirm-registration */
        $confirmationLink = sprintf('%s?token=%s', 'https://localhost/api/authentication/confirm-registration', $token);

        return (new Email())
        ->from('noreply@veliu.net')
        ->to($to->value)
        ->subject(sprintf('Do wurdest von %s eingeladen', $invitedByUser->email->value))
        ->text(sprintf('Bitte bestätigen Sie ihre Einladung und Registrierung! %s', $confirmationLink));
    }
}
