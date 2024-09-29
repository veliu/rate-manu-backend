<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Veliu\RateManu\Domain\Comment\CommentCollection;

final readonly class CommentCollectionResponse
{
    /**
     * @phpstan-param list<CommentResponse> $items
     */
    private function __construct(
        #[OA\Property(type: 'integer')]
        public int $count,
        #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: CommentResponse::class)))]
        public array $items,
    ) {
    }

    public static function fromDomainCollection(CommentCollection $collection): self
    {
        $foodEntries = [];
        foreach ($collection as $item) {
            $foodEntries[] = CommentResponse::fromEntity($item);
        }

        return new self($collection->total, $foodEntries);
    }
}
