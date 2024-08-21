<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

use function Psl\Type\non_empty_string;

final readonly class ConfirmUserRegistrationRequest
{
    public function __construct(
        #[OA\Property(type: 'string', format: 'jwt')]
        #[Assert\NotBlank]
        public mixed $token,
    ) {
    }

    /** @phpstan-return non-empty-string */
    public function getToken(): string
    {
        return non_empty_string()->coerce($this->token);
    }
}
