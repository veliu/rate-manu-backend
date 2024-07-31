<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\User;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Veliu\RateManu\Application\ValueResolver\EmailAddressValueResolver;
use Veliu\RateManu\Domain\User\Command\InviteUser;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;

#[Route(path: '/invite/{emailAddress}', methods: ['POST'], format: 'application/json')]
final readonly class InviteUserAction
{
    public function __construct(
        private MessageBusInterface $messageBus
    ) {
    }

    public function __invoke(
        #[ValueResolver(EmailAddressValueResolver::class)] EmailAddress $emailAddress,
        UserInterface $user,
    ): JsonResponse {
        $this->messageBus->dispatch(new InviteUser($emailAddress, EmailAddress::fromAny($user->getUserIdentifier())));

        return new JsonResponse(null, 204);
    }
}
