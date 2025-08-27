<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Veliu\RateManu\Domain\Food\Command\CreateFood;
use Veliu\RateManu\Domain\Group\Group;
use Veliu\RateManu\Domain\User\User;

final readonly class CreateFoodRequest
{
    public function __construct(
        #[OA\Property(description: 'Will be generated if not provided', type: 'string', format: 'uuid')]
        #[Assert\AtLeastOneOf([
            new Assert\Uuid(),
            new Assert\IsNull(),
        ])]
        #[Assert\NotBlank(allowNull: true)]
        public ?string $id,

        #[OA\Property(type: 'string')]
        #[Assert\NotBlank]
        public ?string $name,

        #[OA\Property(type: 'string', nullable: true)]
        #[Assert\NotBlank(allowNull: true)]
        public ?string $description,
    ) {
    }

    public function toDomainCommand(Group $group, User $user): CreateFood
    {
        $id = $this->id;
        $name = $this->name;
        $description = $this->description;

        \Webmozart\Assert\Assert::nullOrUuid($id);
        \Webmozart\Assert\Assert::stringNotEmpty($name);
        \Webmozart\Assert\Assert::nullOrStringNotEmpty($description);

        return new CreateFood(
            $id ? Uuid::fromString($id) : Uuid::v4(),
            $name,
            $description,
            $group,
            $user
        );
    }
}
