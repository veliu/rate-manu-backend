<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Exception\NotFoundException;
use Veliu\RateManu\Domain\Food\Food;
use Veliu\RateManu\Domain\Food\FoodRepositoryInterface;

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

    public function create(Food $food): void
    {
        $this->getEntityManager()->persist($food);
        $this->getEntityManager()->flush();
    }
}