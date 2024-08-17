<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\User;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Veliu\RateManu\Application\Response\UserResponse;
use Veliu\RateManu\Domain\User\UserRepositoryInterface;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;

#[OA\Tag('User')]
#[Route(path: '/me', methods: ['GET'], format: 'json')]
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

        return new JsonResponse((array) UserResponse::fromEntity($user), 200);
    }
}
