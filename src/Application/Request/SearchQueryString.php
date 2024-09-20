<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Veliu\RateManu\Domain\FilterOperator;
use Veliu\RateManu\Domain\SearchCriteria;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\numeric_string;
use function Psl\Type\positive_int;
use function Psl\Type\uint;
use function Psl\Type\union;

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

        #[Assert\All([
            new Assert\Collection(fields: [
                'entity' => [
                    new Assert\Type('string'),
                    new Assert\NotBlank(allowNull: false),
                ],
                'propertyName' => [
                    new Assert\Type('string'),
                    new Assert\NotBlank(allowNull: false),
                ],
                'operator' => [
                    new Assert\Type('string'),
                    new Assert\NotBlank(allowNull: false),
                ],
                'value' => [
                    new Assert\Type(['string', 'integer', 'numeric', 'bool']),
                ],
            ]),
        ])]
        #[OA\Property(type: 'array', items: new OA\Items(new Model(type: Filter::class)))]
        public array $filter = [],
    ) {
    }

    public function toSearchCriteria(User $user): SearchCriteria
    {
        $offset = (int) union(uint(), numeric_string())->coerce($this->offset);
        $limit = (int) union(uint(), numeric_string())->coerce($this->limit);
        $sorting = [];
        $filter = [];

        foreach ($this->sorting as $sort) {
            $sorting[] = new \Veliu\RateManu\Domain\Sorting($sort['propertyName'], $sort['direction']);
        }

        foreach ($this->filter as $f) {
            $filter[] = new \Veliu\RateManu\Domain\Filter(
                $f['entity'],
                $f['propertyName'],
                FilterOperator::from($f['operator']),
                $f['value']
            );
        }

        return new SearchCriteria(
            userId: $user->id,
            sorting: $sorting,
            filter: $filter,
            offset: uint()->coerce($offset),
            limit: positive_int()->coerce($limit),
        );
    }
}
