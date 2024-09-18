<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Request;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Veliu\RateManu\Domain\SearchCriteria;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\int;
use function Psl\Type\literal_scalar;
use function Psl\Type\null;
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
    ) {
    }

    public function toSearchCriteria(User $user): SearchCriteria
    {
        $offset = (int) union(uint(), numeric_string())->coerce($this->offset);
        $limit = (int) union(uint(), numeric_string())->coerce($this->limit);
        $sorting = [];

        foreach ($this->sorting as $sort) {
            $sorting[] = new \Veliu\RateManu\Domain\Sorting($sort['propertyName'], $sort['direction']);
        }

        return new SearchCriteria(
            userId: $user->id,
            sorting: $sorting,
            offset: uint()->coerce($offset),
            limit: positive_int()->coerce($limit),
        );
    }
}
