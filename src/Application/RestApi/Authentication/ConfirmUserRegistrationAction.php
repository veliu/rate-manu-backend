<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\Authentication;

use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Veliu\RateManu\Domain\User\Status;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\instance_of;

#[OA\Tag('Authentication')]
#[Route(path: '/confirm-registration', methods: ['POST', 'GET'], format: 'json')]
final readonly class ConfirmUserRegistrationAction
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(UserInterface $user): JsonResponse
    {
        $domainUser = instance_of(User::class)->coerce($user);

        if (Status::PENDING_REGISTRATION !== $domainUser->getStatus()) {
            throw new UnprocessableEntityHttpException('User is not a registered user.');
        }

        $domainUser->setStatus(Status::ACTIVE);

        $this->entityManager->persist($domainUser);
        $this->entityManager->flush();

        return new JsonResponse(null, 204);
    }
}
