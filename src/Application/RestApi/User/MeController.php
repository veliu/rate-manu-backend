<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\User;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Veliu\RateManu\Application\Request\UpdateUserRequest;
use Veliu\RateManu\Application\Response\UserResponse;
use Veliu\RateManu\Domain\User\User;
use Veliu\RateManu\Infra\Doctrine\Repository\UserRepository;

use function Psl\Type\instance_of;

#[OA\Tag('User')]
#[Route(path: '/me', name: 'me')]
final readonly class MeController
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private UserRepository $userRepository,
    ) {
    }

    #[OA\Response(
        response: 200,
        description: 'User data',
        content: new Model(type: UserResponse::class)
    )]
    #[Route(name: '_get', methods: ['GET'], format: 'json')]
    public function get(UserInterface $authenticatedUser): JsonResponse
    {
        $user = instance_of(User::class)->coerce($authenticatedUser);

        return new JsonResponse((array) UserResponse::fromEntity($user), 200);
    }

    #[OA\Response(
        response: 200,
        description: 'Updated user data',
        content: new Model(type: UserResponse::class)
    )]
    #[Route(name: '_put', methods: ['PUT'], format: 'json')]
    public function put(
        UserInterface $authenticatedUser,
        #[MapRequestPayload] UpdateUserRequest $request
    ): JsonResponse {
        $user = instance_of(User::class)->coerce($authenticatedUser);
        $this->messageBus->dispatch($request->toDomainCommand($user));
        $userResponse = UserResponse::fromEntity($this->userRepository->get($user->id));

        return new JsonResponse($userResponse, 200);
    }
}
