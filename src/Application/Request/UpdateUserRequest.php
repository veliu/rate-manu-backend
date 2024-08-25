<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Veliu\RateManu\Domain\User\Command\UpdateUser;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\non_empty_string;

final readonly class UpdateUserRequest
{
    public function __construct(
        #[OA\Property(type: 'string')]
        #[Assert\Type('string')]
        #[Assert\NotBlank(allowNull: false)]
        public mixed $name
    ) {
    }

    public function toDomainCommand(User $user): UpdateUser
    {
        $name = non_empty_string()->coerce($this->name);

        return new UpdateUser($user->id, $name);
    }
}
