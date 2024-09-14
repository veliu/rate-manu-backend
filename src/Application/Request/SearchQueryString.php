<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Veliu\RateManu\Domain\SearchCriteria;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\positive_int;

final readonly class SearchQueryString
{
    public function __construct(
        #[Assert\Type('numeric')]
        #[Assert\Range(min: 0, max: 100)]
        #[OA\Property(type: 'number', default: 0, maximum: 100, minimum: 0)]
        public mixed $offset = 0,

        #[Assert\Type('numeric')]
        #[Assert\Range(min: 0, max: 500)]
        #[OA\Property(type: 'number', default: 10, maximum: 500, minimum: 0)]
        public mixed $limit = 10,

        #[Assert\All([
            new Assert\Collection(fields: [
                'propertyName' => [
                    new Assert\Type('string'),
                    new Assert\NotBlank(allowNull: false),
                ],
                'direction' => [
                    new Assert\Type('string'),
                    new Assert\NotBlank(allowNull: false),
                    new Assert\Choice(['desc', 'asc']),
                ],
            ]),
        ])]
        #[OA\Property(type: 'array', items: new OA\Items(new Model(type: Sorting::class)))]
        public array $sorting = [],
    ) {
    }

    public function toSearchCriteria(User $user): SearchCriteria
    {
        $offset = (int) $this->offset ?? 0;
        $limit = (int) $this->limit ?? 10;
        $sorting = [];

        foreach ($this->sorting as $sort) {
            $sorting[] = new \Veliu\RateManu\Domain\Sorting($sort['propertyName'], $sort['direction']);
        }

        return new SearchCriteria(
            userId: $user->id,
            sorting: $sorting,
            offset: positive_int()->coerce($offset),
            limit: positive_int()->coerce($limit),
        );
    }
}
