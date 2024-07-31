<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\User\Exception\UserNotCreatedException;
use Veliu\RateManu\Domain\User\Exception\UserNotFoundException;
use Veliu\RateManu\Domain\User\User;
use Veliu\RateManu\Domain\User\UserRepositoryInterface;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function create(User $user): void
    {
        $email = $user->email;

        try {
            $this->getByEmail($email);
            throw UserNotCreatedException::userWithEmailAlreadyExists($email);
        } catch (UserNotFoundException) {
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
        }
    }

    public function get(Uuid $uuid): User
    {
        $user = $this->find($uuid);

        if (null === $user) {
            throw UserNotFoundException::byUuid($uuid);
        }

        return $user;
    }

    public function getByEmail(EmailAddress $email): User
    {
        $user = $this->findOneBy(['email' => $email]);

        if (null === $user) {
            throw UserNotFoundException::byEmail($email);
        }

        return $user;
    }
}
