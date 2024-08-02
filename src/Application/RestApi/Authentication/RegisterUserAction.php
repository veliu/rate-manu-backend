<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\Authentication;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Veliu\RateManu\Application\Request\RegisterUserRequest;

#[Route(path: '/register', methods: ['POST'], format: 'json')]
#[OA\Response(response: 204, description: 'User registered successfully.', content: null)]
#[OA\Response(response: 422, description: 'Validation errors.')]
final readonly class RegisterUserAction
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload(acceptFormat: 'json')] RegisterUserRequest $registerUserRequest
    ): JsonResponse {
        $this->messageBus->dispatch($registerUserRequest->toDomainCommand());

        return new JsonResponse(null, 204);
    }
}
