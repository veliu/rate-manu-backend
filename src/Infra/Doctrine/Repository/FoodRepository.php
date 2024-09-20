<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Exception\NotFoundException;
use Veliu\RateManu\Domain\Food\Food;
use Veliu\RateManu\Domain\Food\FoodCollection;
use Veliu\RateManu\Domain\Food\FoodRepositoryInterface;
use Veliu\RateManu\Domain\Rating\Rating;
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
        $foodEntity = 'food';

        $qb = $this->createQueryBuilder($foodEntity);

        $ratingEntity = 'rating';
        $groupRelationEntity = 'group_relation';

        $qb->join(GroupRelation::class, $groupRelationEntity, Join::WITH, sprintf('%s.group = %s.group', $foodEntity, $groupRelationEntity))
            ->where(sprintf('%s.user = :userId', $groupRelationEntity))
            ->setParameter('userId', $searchCriteria->userId);

        foreach ($searchCriteria->sorting as $sorting) {
            if ('averageRating' === $sorting->propertyName) {
                $qb
                    ->leftJoin(Rating::class, $ratingEntity, Join::WITH, sprintf('%s.id = %s.food', $foodEntity, $ratingEntity))
                    ->groupBy('food')
                    ->orderBy(sprintf('AVG(%s.rating)', $ratingEntity), $sorting->direction);
                continue;
            }
            $qb->addOrderBy(sprintf('%s.%s', $foodEntity, $sorting->propertyName), $sorting->direction);
        }

        foreach ($searchCriteria->filter as $filter) {
            $parameter = sprintf(':%s', $filter->propertyName);
            $qb->andWhere(sprintf('%s.%s %s %s', $filter->entity, $filter->propertyName, $filter->operator->value, $parameter));
            $qb->setParameter($parameter, $filter->value);
        }

        $query = $qb->getQuery();
        $paginator = new Paginator($query);
        $totalItems = count($paginator);

        $paginator
            ->getQuery()
            ->setFirstResult($searchCriteria->offset)
            ->setMaxResults($searchCriteria->limit);

        return new FoodCollection($paginator->getIterator(), $totalItems);
    }

    public function findByUser(User $user): FoodCollection
    {
        $qb = $this->createQueryBuilder('food');

        $qb->join(GroupRelation::class, 'rel', Join::WITH, 'food.group = rel.group')
            ->where('rel.user = :userId')
            ->setParameter('userId', $user->id);

        return new FoodCollection($qb->getQuery()->toIterable());
    }
}
