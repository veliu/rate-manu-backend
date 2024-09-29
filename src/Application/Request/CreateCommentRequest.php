<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Veliu\RateManu\Domain\Comment\Command\CreateComment;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\non_empty_string;

final readonly class CreateCommentRequest
{
    public function __construct(
        #[OA\Property(type: 'string')]
        #[Assert\Type('string')]
        #[Assert\NotBlank(allowNull: false)]
        public mixed $comment,
    ) {
    }

    public function toDomainCommand(User $user, Uuid $foodId): CreateComment
    {
        $comment = non_empty_string()->coerce($this->comment);

        return new CreateComment(Uuid::v4(), $foodId, $user->id, $comment);
    }
}
