<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class Filter
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[OA\Property(type: 'string')]
        public mixed $entity,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[OA\Property(type: 'string')]
        public mixed $propertyName,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[OA\Property(type: 'string')]
        public mixed $operator,

        #[Assert\Type(['string', 'integer', 'numeric', 'bool'])]
        #[OA\Property(type: ['string', 'number', 'integer', 'bool'])]
        public mixed $value,
    ) {
    }
}
