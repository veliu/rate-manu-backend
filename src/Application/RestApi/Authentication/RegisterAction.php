<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\Authentication;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Veliu\RateManu\Application\Request\RegisterUserRequest;

#[Route(path: '/register', methods: ['POST'], format: 'application/json')]
final readonly class RegisterAction
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload(acceptFormat: 'json')] RegisterUserRequest $registerUserRequest
    ): JsonResponse {
        $this->messageBus->dispatch($registerUserRequest->toDomainCommand());

        return new JsonResponse(['hallo' => 'test'], 208);
    }
}
