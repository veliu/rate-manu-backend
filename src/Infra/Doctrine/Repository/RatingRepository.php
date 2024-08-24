<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Exception\NotFoundException;
use Veliu\RateManu\Domain\Food\Food;
use Veliu\RateManu\Domain\Rating\Rating;
use Veliu\RateManu\Domain\Rating\RatingCollection;
use Veliu\RateManu\Domain\Rating\RatingRepositoryInterface;
use Veliu\RateManu\Domain\User\GroupRelation;
use Veliu\RateManu\Domain\User\User;

/**
 * @extends ServiceEntityRepository<Rating>
 *
 * @method Rating|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rating|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rating[]    findAll()
 * @method Rating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class RatingRepository extends ServiceEntityRepository implements RatingRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    public function get(Uuid $uuid): Rating
    {
        if (!$result = $this->find($uuid)) {
            throw new NotFoundException(sprintf('Rating with ID "%s" not found', $uuid->toString()));
        }

        return $result;
    }

    public function getByUserAndFood(User $user, Food $food): Rating
    {
        if (!$result = $this->findOneBy(['createdBy' => $user, 'food' => $food])) {
            throw new NotFoundException(sprintf('No rating exists for user "%s" and food "%s"', $user->id->toString(), $food->id->toString()));
        }

        return $result;
    }

    public function findForAllMembers(User $user, Food $food): RatingCollection
    {
        $qb = $this->createQueryBuilder('rating');

        $qb
            ->join(Food::class, 'food', Join::WITH, 'food.id = rating.food')
            ->join(GroupRelation::class, 'rel', Join::WITH, 'food.group = rel.group')
            ->andWhere('rel.user = :userId')
            ->andWhere('food.id = :foodId')
            ->setParameter('userId', $user->id)
            ->setParameter('foodId', $food->id);

        return new RatingCollection($qb->getQuery()->toIterable());
    }

    public function save(Rating $rating): void
    {
        $this->getEntityManager()->persist($rating);
        $this->getEntityManager()->flush();
    }
}
