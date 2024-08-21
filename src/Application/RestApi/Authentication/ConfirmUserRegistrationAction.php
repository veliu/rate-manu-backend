<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\Authentication;

use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Veliu\RateManu\Application\Request\ConfirmUserRegistrationRequest;
use Veliu\RateManu\Domain\User\Status;
use Veliu\RateManu\Domain\User\UserRepositoryInterface;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;

#[OA\Tag('Authentication')]
#[Route(path: '/confirm-registration', methods: ['POST', 'GET'], format: 'json')]
final readonly class ConfirmUserRegistrationAction
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EntityManagerInterface $entityManager,
        private JWTTokenManagerInterface $tokenManager,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] ConfirmUserRegistrationRequest $request
    ): JsonResponse {
        $token = $this->tokenManager->parse($request->getToken());

        if (empty($token['username'])) {
            throw new UnprocessableEntityHttpException();
        }

        $email = EmailAddress::fromAny($token['username']);

        $domainUser = $this->userRepository->getByEmail($email);

        if (Status::PENDING_REGISTRATION !== $domainUser->getStatus()) {
            throw new UnprocessableEntityHttpException('User is not a registered user.');
        }

        $domainUser->setStatus(Status::ACTIVE);

        $this->entityManager->persist($domainUser);
        $this->entityManager->flush();

        return new JsonResponse(['token' => $this->tokenManager->create($domainUser)], 200);
    }
}
