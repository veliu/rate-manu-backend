<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Veliu\RateManu\Domain\Food\Command\CreateFood;
use Veliu\RateManu\Domain\Group\Group;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\non_empty_string;
use function Psl\Type\null;
use function Psl\Type\union;

final readonly class CreateFoodRequest
{
    public function __construct(
        #[OA\Property(description: 'Will be generated if not provided', type: 'string', format: 'uuid')]
        #[Assert\AtLeastOneOf([
            new Assert\Uuid(),
            new Assert\IsNull(),
        ])]
        #[Assert\NotBlank(allowNull: true)]
        public mixed $id,

        #[OA\Property(type: 'string')]
        #[Assert\NotBlank]
        public mixed $name,

        #[OA\Property(type: 'string', nullable: true)]
        #[Assert\NotBlank(allowNull: true)]
        public mixed $description,
    ) {
    }

    public function toDomainCommand(Group $group, User $user): CreateFood
    {
        $id = non_empty_string()->matches($this->id)
            ? Uuid::fromString($this->id)
            : Uuid::v4();

        $name = non_empty_string()->coerce($this->name);
        $description = union(non_empty_string(), null())->coerce($this->description);

        return new CreateFood($id, $name, $description, $group, $user);
    }
}
