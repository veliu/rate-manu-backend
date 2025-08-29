<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\User;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Veliu\RateManu\Application\Request\InviteUserRequest;
use Veliu\RateManu\Domain\User\Exception\GroupNotFoundException;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\instance_of;

#[OA\Tag('User')]
#[OA\Response(response: 204, description: 'User invited')]
#[OA\Response(response: 422, description: 'Validation Error')]
#[Route(path: '/invite', name: 'user_invite', methods: ['POST'], format: 'json')]
final readonly class InviteUserAction
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload(acceptFormat: 'json')] InviteUserRequest $requestPayload,
        UserInterface $user,
    ): JsonResponse {
        $domainUser = instance_of(User::class)->coerce($user);

        $command = $requestPayload->toDomainCommand($domainUser);

        try {
            $this->messageBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            if ($e->getPrevious() instanceof GroupNotFoundException) {
                throw new UnprocessableEntityHttpException('Group does not exist');
            }
            $e->getPrevious() ? throw $e->getPrevious() : throw $e;
        }

        return new JsonResponse(null, 204);
    }
}
