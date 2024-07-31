<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Group\Group;
use Veliu\RateManu\Domain\User\Command\ConfirmUserRegistration;
use Veliu\RateManu\Domain\UserRepositoryInterface;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;

#[AsMessageHandler]
final readonly class ConfirmUserRegistrationHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private JWTTokenManagerInterface $tokenManager,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(ConfirmUserRegistration $command): void
    {
        $payload = $this->tokenManager->parse($command->token);

        if (!array_key_exists('username', $payload)) {
            throw new AuthenticationException('Confirmation not valid');
        }

        $email = EmailAddress::fromAny($payload['username']);

        $user = $this->userRepository->getByEmail($email);

        $user->activate();

        $group = new Group(Uuid::v4(), 'Veliu');
        $group->addMember($user);

        $this->entityManager->persist($user);
        $this->entityManager->persist($group);
        $this->entityManager->flush();
    }
}
