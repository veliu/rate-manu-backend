<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Veliu\RateManu\Domain\User\Command\ConfirmUserRegistration;
use Veliu\RateManu\Domain\User\UserRepositoryInterface;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;

#[AsMessageHandler]
final readonly class ConfirmUserRegistrationHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private JWTEncoderInterface $encoder,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(ConfirmUserRegistration $command): void
    {
        $payload = $this->encoder->decode($command->token);

        if (!array_key_exists('username', $payload)) {
            throw new AuthenticationException('Confirmation not valid');
        }

        $email = EmailAddress::fromAny($payload['username']);

        $user = $this->userRepository->getByEmail($email);

        $user->activate();

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
