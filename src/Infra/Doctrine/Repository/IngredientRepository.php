<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Exception\NotFoundException;
use Veliu\RateManu\Domain\Filter;
use Veliu\RateManu\Domain\Ingredient\Ingredient;
use Veliu\RateManu\Domain\Ingredient\IngredientEntityCollection;
use Veliu\RateManu\Domain\Ingredient\IngredientRepositoryInterface;
use Veliu\RateManu\Domain\SearchCriteria;
use Veliu\RateManu\Domain\User\GroupRelation;

/**
 * @extends ServiceEntityRepository<Ingredient>
 *
 * @method Ingredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingredient[]    findAll()
 * @method Ingredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class IngredientRepository extends ServiceEntityRepository implements IngredientRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ingredient::class);
    }

    public function get(Uuid $uuid): Ingredient
    {
        if (!$result = $this->find($uuid)) {
            throw new NotFoundException(sprintf('Ingredient with ID "%s" not found', $uuid->toString()));
        }

        return $result;
    }

    public function delete(Ingredient $food): void
    {
        $this->getEntityManager()->remove($food);
        $this->getEntityManager()->flush();
    }

    public function upsert(Ingredient $food): void
    {
        $this->getEntityManager()->persist($food);
        $this->getEntityManager()->flush();
    }

    public function search(SearchCriteria $searchCriteria): IngredientEntityCollection
    {
        $ingredientEntity = 'ingredient';

        $qb = $this->createQueryBuilder($ingredientEntity);

        $qb->join(
            join: GroupRelation::class,
            alias: 'group_relation',
            conditionType: Join::WITH,
            condition: 'ingredient.group = group_relation.group'
        )
            ->where('group_relation.user = :userId')
            ->setParameter('userId', $searchCriteria->userId);

        // Apply filters
        foreach ($searchCriteria->filter as $filter) {
            $this->applyFilter($qb, $filter, $ingredientEntity);
        }

        // Apply sorting
        foreach ($searchCriteria->sorting as $sorting) {
            $qb->addOrderBy(
                sprintf('%s.%s', $ingredientEntity, $sorting->propertyName),
                $sorting->direction
            );
        }

        // Default sorting by name if no sorting specified
        if (empty($searchCriteria->sorting)) {
            $qb->orderBy(sprintf('%s.name', $ingredientEntity), 'ASC');
        }

        // Apply pagination
        $qb->setFirstResult($searchCriteria->offset)
            ->setMaxResults($searchCriteria->limit);

        $paginator = new Paginator($qb->getQuery());

        return new IngredientEntityCollection($paginator->getIterator()->getArrayCopy());
    }

    private function applyFilter(QueryBuilder $qb, Filter $filter, string $entityAlias): void
    {
        $paramName = 'filter_'.$filter->propertyName;

        switch ($filter->operator->value) {
            case 'eq':
                $qb->andWhere(sprintf('%s.%s = :%s', $entityAlias, $filter->propertyName, $paramName))
                    ->setParameter($paramName, $filter->value);
                break;
            case 'like':
                $qb->andWhere(sprintf('%s.%s LIKE :%s', $entityAlias, $filter->propertyName, $paramName))
                    ->setParameter($paramName, '%'.$filter->value.'%');
                break;
            case 'gt':
                $qb->andWhere(sprintf('%s.%s > :%s', $entityAlias, $filter->propertyName, $paramName))
                    ->setParameter($paramName, $filter->value);
                break;
            case 'gte':
                $qb->andWhere(sprintf('%s.%s >= :%s', $entityAlias, $filter->propertyName, $paramName))
                    ->setParameter($paramName, $filter->value);
                break;
            case 'lt':
                $qb->andWhere(sprintf('%s.%s < :%s', $entityAlias, $filter->propertyName, $paramName))
                    ->setParameter($paramName, $filter->value);
                break;
            case 'lte':
                $qb->andWhere(sprintf('%s.%s <= :%s', $entityAlias, $filter->propertyName, $paramName))
                    ->setParameter($paramName, $filter->value);
                break;
            case 'in':
                $qb->andWhere(sprintf('%s.%s IN (:%s)', $entityAlias, $filter->propertyName, $paramName))
                    ->setParameter($paramName, $filter->value);
                break;
            case 'not_in':
                $qb->andWhere(sprintf('%s.%s NOT IN (:%s)', $entityAlias, $filter->propertyName, $paramName))
                    ->setParameter($paramName, $filter->value);
                break;
            case 'is_null':
                $qb->andWhere(sprintf('%s.%s IS NULL', $entityAlias, $filter->propertyName));
                break;
            case 'is_not_null':
                $qb->andWhere(sprintf('%s.%s IS NOT NULL', $entityAlias, $filter->propertyName));
                break;
        }
    }
}
