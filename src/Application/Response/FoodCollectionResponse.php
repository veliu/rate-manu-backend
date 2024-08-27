<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Veliu\RateManu\Domain\Food\FoodCollection;
use Veliu\RateManu\Domain\User\User;

final readonly class FoodCollectionResponse
{
    /**
     * @phpstan-param list<FoodResponse> $items
     */
    private function __construct(
        #[OA\Property(type: 'integer')]
        public int $count,
        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: FoodResponse::class)))]
        public array $items,
    ) {
    }

    public static function fromDomainCollection(FoodCollection $collection, User $user, string $domain = 'https://api.ratemanu.com/'): self
    {
        $count = 0;
        $foodEntries = [];
        foreach ($collection as $item) {
            $foodEntries[] = FoodResponse::fromEntity($item, $user, $domain);
            ++$count;
        }

        return new self($count, $foodEntries);
    }
}
