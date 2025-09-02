<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Exception\NotFoundException;
use Veliu\RateManu\Domain\Food\Ingredient\FoodIngredient;
use Veliu\RateManu\Domain\Food\Ingredient\FoodIngredientEntityCollection;
use Veliu\RateManu\Domain\Food\Ingredient\FoodIngredientRepositoryInterface;

/**
 * @extends ServiceEntityRepository<FoodIngredient>
 *
 * @method FoodIngredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method FoodIngredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method FoodIngredient[]    findAll()
 * @method FoodIngredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class FoodIngredientRepository extends ServiceEntityRepository implements FoodIngredientRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FoodIngredient::class);
    }

    public function get(Uuid $uuid): FoodIngredient
    {
        if (!$result = $this->find($uuid)) {
            throw new NotFoundException(sprintf('FoodIngredient with ID "%s" not found', $uuid->toString()));
        }

        return $result;
    }

    public function delete(FoodIngredient $food): void
    {
        $this->getEntityManager()->remove($food);
        $this->getEntityManager()->flush();
    }

    public function upsert(FoodIngredient $food): void
    {
        $this->getEntityManager()->persist($food);
        $this->getEntityManager()->flush();
    }

    public function findByFood(Uuid $foodId): FoodIngredientEntityCollection
    {
        $result = $this->findBy(['food' => $foodId]);

        return new FoodIngredientEntityCollection($result);
    }

    public function findByFoodAndIngredient(Uuid $foodId, Uuid $ingredientId): ?FoodIngredient
    {
        return $this->findOneBy(['food' => $foodId, 'ingredient' => $ingredientId]);
    }
}
