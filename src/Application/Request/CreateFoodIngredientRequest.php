<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Veliu\RateManu\Domain\Food\Ingredient\Command\CreateFoodIngredient;
use Veliu\RateManu\Domain\Ingredient\Ingredient;
use Veliu\RateManu\Domain\Ingredient\UnitEnum;
use Veliu\RateManu\Infra\Symfony\Validator\Constraint\EntityExists;

final readonly class CreateFoodIngredientRequest
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

        #[OA\Property(type: 'string', format: 'uuid')]
        #[Assert\Uuid]
        #[Assert\NotBlank]
        #[EntityExists(entityClass: Ingredient::class)]
        public string $ingredientId,

        #[OA\Property(
            description: 'If not provided, default unit will be used',
            type: 'string',
            enum: UnitEnum::VALUES,
            nullable: true
        )]
        #[Assert\NotBlank(allowNull: true)]
        public ?string $unit,

        #[Assert\Positive]
        #[Assert\Type(['numeric'])]
        #[OA\Property(
            description: 'Numeric value. Will be converted to floating point if needed.',
            type: 'number',
        )]
        public float|int|string $amount,
    ) {
    }

    public function toDomainCommand(Uuid $foodId): CreateFoodIngredient
    {
        $id = $this->id;
        $ingredientId = $this->ingredientId;
        $unit = $this->unit;
        $amount = $this->amount;

        \Webmozart\Assert\Assert::nullOrUuid($id);
        \Webmozart\Assert\Assert::uuid($ingredientId);
        \Webmozart\Assert\Assert::nullOrStringNotEmpty($unit);
        \Webmozart\Assert\Assert::numeric($amount);

        return new CreateFoodIngredient(
            $id ? Uuid::fromString($id) : Uuid::v4(),
            $foodId,
            Uuid::fromString($ingredientId),
            (float) $amount,
        );
    }
}
