<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Veliu\RateManu\Domain\FoodRating\FoodRating;
use Veliu\RateManu\Domain\FoodRating\FoodRatingRepositoryInterface;

/**
 * @extends ServiceEntityRepository<FoodRating>
 *
 * @method FoodRating|null find($id, $lockMode = null, $lockVersion = null)
 * @method FoodRating|null findOneBy(array $criteria, array $orderBy = null)
 * @method FoodRating[]    findAll()
 * @method FoodRating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class FoodRatingRepository extends ServiceEntityRepository implements FoodRatingRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FoodRating::class);
    }
}
