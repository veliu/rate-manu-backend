<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class Sorting
{
    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[OA\Property(type: 'string')]
        public mixed $propertyName,

        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Choice(['asc', 'desc'])]
        #[OA\Property(type: 'string', enum: ['asc', 'desc'])]
        public mixed $direction,
    ) {
    }
}
