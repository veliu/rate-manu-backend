<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Veliu\RateManu\Domain\Food\Command\UpdateFood;
use Veliu\RateManu\Domain\Food\Food;

use function Psl\Type\non_empty_string;
use function Psl\Type\null;
use function Psl\Type\nullable;
use function Psl\Type\union;

final readonly class UpdateFoodRequest
{
    public function __construct(
        #[OA\Property(type: 'string')]
        #[Assert\NotBlank]
        public mixed $name,

        #[OA\Property(type: 'string', nullable: true)]
        #[Assert\NotBlank(allowNull: true)]
        public mixed $description,
    ) {
    }

    public function toDomainCommand(Food $entity): UpdateFood
    {
        $name = non_empty_string()->coerce($this->name);
        $description = nullable(non_empty_string())->coerce($this->description);

        return new UpdateFood($entity->id, $name, $description);
    }
}
