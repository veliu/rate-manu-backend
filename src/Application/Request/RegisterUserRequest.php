<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Veliu\RateManu\Domain\User\Command\RegisterUser;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;

use function Psl\Type\non_empty_string;

final readonly class RegisterUserRequest
{
    public function __construct(
        #[OA\Property(type: 'string', format: 'email')]
        #[Assert\Email]
        #[Assert\NotBlank]
        public mixed $email,

        #[OA\Property(type: 'string', format: 'password', minLength: 8, pattern: '^(?=.*[A-Za-z])(?=.*\d)(?=.*[A-Z]).{8,}$')]
        #[Assert\NotBlank]
        #[Assert\PasswordStrength]
        public mixed $password,
    ) {
    }

    public function toDomainCommand(): RegisterUser
    {
        $email = non_empty_string()->coerce($this->email);
        $password = non_empty_string()->coerce($this->password);

        return new RegisterUser(EmailAddress::fromAny($email), $password);
    }
}
