<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Comment\Comment;
use Veliu\RateManu\Domain\Comment\CommentCollection;
use Veliu\RateManu\Domain\Comment\CommentRepositoryInterface;
use Veliu\RateManu\Domain\Exception\NotFoundException;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class CommentRepository extends ServiceEntityRepository implements CommentRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function get(Uuid $id): Comment
    {
        if (!$result = $this->find($id)) {
            throw new NotFoundException(sprintf('Comment with ID "%s" not found', $id->toString()));
        }

        return $result;
    }

    public function getForFood(Uuid $foodId): CommentCollection
    {
        $result = $this->findBy(['food' => $foodId]);

        return new CommentCollection($result, count($result));
    }
}
