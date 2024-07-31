<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\Authentication;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Veliu\RateManu\Application\Response\User;
use Veliu\RateManu\Domain\UserRepositoryInterface;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;

#[Route(path: '/me', methods: ['GET'], format: 'application/json')]
final readonly class MeAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(UserInterface $authenticatedUser): JsonResponse
    {
        $user = $this->userRepository->getByEmail(
            EmailAddress::fromAny($authenticatedUser->getUserIdentifier())
        );

        return new JsonResponse((array) User::fromEntity($user), 200);
    }
}
