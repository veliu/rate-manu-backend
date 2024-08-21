<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Veliu\RateManu\Domain\User\Command\InviteUserToGroup;
use Veliu\RateManu\Domain\User\User;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;

use function Psl\Type\non_empty_string;

final readonly class InviteUserRequest
{
    public function __construct(
        #[OA\Property(type: 'string', format: 'email')]
        #[Assert\Email]
        #[Assert\NotBlank]
        public mixed $email,

        #[OA\Property(type: 'string', format: 'uuid')]
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public mixed $group,
    ) {
    }

    public function toDomainCommand(User $invitedBy): InviteUserToGroup
    {
        $email = non_empty_string()->coerce($this->email);
        $group = non_empty_string()->coerce($this->group);

        return new InviteUserToGroup(EmailAddress::fromAny($email), $invitedBy->id, Uuid::fromString($group));
    }
}
