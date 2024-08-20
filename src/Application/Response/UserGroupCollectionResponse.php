<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use Doctrine\Common\Collections\Collection;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Veliu\RateManu\Domain\Group\Group;

final readonly class UserGroupCollectionResponse
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

    /** @param Collection<string, Group> $collection */
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
