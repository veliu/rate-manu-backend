<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Veliu\RateManu\Domain\User\Command\RegisterUser;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;

use function Psl\Type\non_empty_string;

final readonly class RegisterUserRequest
{
    public function __construct(
        #[Assert\Email]
        private mixed $email,

        #[Assert\NotBlank]
        #[Assert\PasswordStrength]
        private mixed $password,
    ) {
    }

    public function toDomainCommand(): RegisterUser
    {
        $email = non_empty_string()->coerce($this->email);
        $password = non_empty_string()->coerce($this->password);

        return new RegisterUser(new EmailAddress($email), $password);
    }
}
