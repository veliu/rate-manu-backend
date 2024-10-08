<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Notification\Mail;

use Symfony\Component\Mime\Email;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;

final readonly class RegistrationConfirmationMail
{
    private function __construct()
    {
    }

    public static function create(EmailAddress $to, string $token): Email
    {
        $confirmationLink = sprintf('%s?token=%s', 'https://www.ratemanu.com/confirm-registration', $token);

        return (new Email())
        ->from('noreply@veliu.net')
        ->to($to->value)
        ->subject('Confirm your registration')
        ->text(sprintf('Bitte bestätigen Sie ihre Registrierung! %s', $confirmationLink));
    }
}
