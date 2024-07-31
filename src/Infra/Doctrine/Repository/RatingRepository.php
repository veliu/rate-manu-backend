<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Exception\NotFoundException;
use Veliu\RateManu\Domain\Rating\Rating;
use Veliu\RateManu\Domain\Rating\RatingRepositoryInterface;

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

    public function create(Rating $rating): void
    {
        $this->getEntityManager()->persist($rating);
        $this->getEntityManager()->flush();
    }
}
