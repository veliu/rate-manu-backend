<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Veliu\RateManu\Domain\Exception\NotAllowedException;
use Veliu\RateManu\Domain\Group\Group;
use Veliu\RateManu\Domain\Ingredient\Command\CreateIngredient;
use Veliu\RateManu\Domain\Ingredient\UnitEnum;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\instance_of;
use function Psl\Type\non_empty_string;

final readonly class CreateIngredientRequest
{
    public function __construct(
        #[OA\Property(
            description: 'Will be generated if not provided',
            type: 'string',
            format: 'uuid',
            nullable: true,
        )]
        #[Assert\AtLeastOneOf([
            new Assert\Uuid(),
            new Assert\IsNull(),
        ])]
        #[Assert\NotBlank(allowNull: true)]
        public ?string $id,

        #[OA\Property(type: 'string')]
        #[Assert\NotBlank]
        public ?string $name,

        #[OA\Property(type: 'string', enum: UnitEnum::VALUES)]
        #[Assert\NotBlank]
        #[Assert\Choice(UnitEnum::VALUES)]
        public ?string $defaultUnit,

        #[OA\Property(
            description: 'Primary group will be used be generated if not provided',
            type: 'string',
            format: 'uuid',
            nullable: true,
        )]
        #[Assert\AtLeastOneOf([
            new Assert\Uuid(),
            new Assert\IsNull(),
        ])]
        #[Assert\NotBlank(allowNull: true)]
        public ?string $groupId,
    ) {
    }

    public function toDomainCommand(User $user): CreateIngredient
    {
        $id = non_empty_string()->matches($this->id)
            ? Uuid::fromString($this->id)
            : Uuid::v4();

        $name = non_empty_string()->coerce($this->name);
        $defaultUnit = UnitEnum::tryFrom($this->defaultUnit);

        try {
            $groupId = Uuid::fromString($this->groupId);
            $group = $user
                ->getGroupRelations()
                ->filter(static fn (Group $group) => $group->getId()->equals($groupId))
                ->first()
                ->group;
            if (!instance_of(Group::class)->matches($group)) {
                throw new NotAllowedException('You are not allowed to create an ingredient in this group.');
            }
        } catch (\Throwable) {
            $group = $user->getGroupRelations()->first()?->group ?? null;
        }

        return new CreateIngredient($id, $name, $defaultUnit, $user, $group);
    }
}
