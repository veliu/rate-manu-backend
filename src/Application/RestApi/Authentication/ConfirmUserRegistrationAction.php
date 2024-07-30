<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\Authentication;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Veliu\RateManu\Domain\User\Command\ConfirmUserRegistration;

#[Route(path: '/confirm-registration', methods: ['POST', 'GET'], format: 'application/json')]
final readonly class ConfirmUserRegistrationAction
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(
        #[MapQueryParameter] string $token
    ): JsonResponse {
        $this->messageBus->dispatch(new ConfirmUserRegistration($token));

        return new JsonResponse(null, 204);
    }
}
