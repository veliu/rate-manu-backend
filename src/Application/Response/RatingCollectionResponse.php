<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Veliu\RateManu\Domain\Rating\RatingCollection;

final readonly class RatingCollectionResponse
{
    /**
     * @phpstan-param list<RatingResponse> $items
     */
    private function __construct(
        #[OA\Property(type: 'integer')]
        public int $count,
        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: RatingResponse::class)))]
        public array $items,
    ) {
    }

    public static function fromDomainCollection(RatingCollection $collection): self
    {
        $count = 0;
        $foodEntries = [];
        foreach ($collection as $item) {
            $foodEntries[] = RatingResponse::fromEntity($item);
            ++$count;
        }

        return new self($count, $foodEntries);
    }
}
