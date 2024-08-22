<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Exception\NotFoundException;
use Veliu\RateManu\Domain\Food\Food;
use Veliu\RateManu\Domain\Food\FoodCollection;
use Veliu\RateManu\Domain\Food\FoodRepositoryInterface;
use Veliu\RateManu\Domain\SearchCriteria;
use Veliu\RateManu\Domain\User\GroupRelation;
use Veliu\RateManu\Domain\User\User;

/**
 * @extends ServiceEntityRepository<Food>
 *
 * @method Food|null find($id, $lockMode = null, $lockVersion = null)
 * @method Food|null findOneBy(array $criteria, array $orderBy = null)
 * @method Food[]    findAll()
 * @method Food[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class FoodRepository extends ServiceEntityRepository implements FoodRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Food::class);
    }

    public function get(Uuid $uuid): Food
    {
        if (!$result = $this->find($uuid)) {
            throw new NotFoundException(sprintf('Food with ID "%s" not found', $uuid->toString()));
        }

        return $result;
    }

    public function delete(Food $food): void
    {
        $this->getEntityManager()->remove($food);
        $this->getEntityManager()->flush();
    }

    public function create(Food $food): void
    {
        $this->getEntityManager()->persist($food);
        $this->getEntityManager()->flush();
    }

    public function search(SearchCriteria $searchCriteria): FoodCollection
    {
        $results = $this->findAll();

        return new FoodCollection($results);
    }

    public function findByUser(User $user): FoodCollection
    {
        $qb = $this->createQueryBuilder('food');

        $qb->select('food')
            ->from(Food::class, 'food')
            ->join(GroupRelation::class, 'rel', Join::WITH, 'food.group = rel.group')
            ->where('rel.user = :userId')
            ->setParameter('userId', $user->id);

        $query = $qb->getQuery();

        $results = $query->getResult();

        return new FoodCollection($results);
    }
}
