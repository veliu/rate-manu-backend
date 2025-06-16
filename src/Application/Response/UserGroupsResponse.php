<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use Doctrine\Common\Collections\Collection;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Veliu\RateManu\Domain\User\GroupRelation;

final readonly class UserGroupsResponse
{
    /**
     * @phpstan-param list<GroupResponse> $items
     */
    private function __construct(
        #[OA\Property(type: 'integer')]
        public int $count,
        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: GroupResponse::class)))]
        public array $items,
    ) {
    }

    /** @param Collection<int, GroupRelation> $collection */
    public static function fromDomainCollection(Collection $collection): self
    {
        $count = 0;
        $foodEntries = [];
        foreach ($collection as $item) {
            $foodEntries[] = GroupResponse::fromEntity($item);
            ++$count;
        }

        return new self($count, $foodEntries);
    }
}
