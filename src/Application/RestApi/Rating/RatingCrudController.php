<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\Rating;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Application\Request\CreateRatingRequest;
use Veliu\RateManu\Application\Request\UpdateRatingRequest;
use Veliu\RateManu\Application\Response\RatingResponse;
use Veliu\RateManu\Domain\Exception\NotFoundException;
use Veliu\RateManu\Domain\Food\FoodRepositoryInterface;
use Veliu\RateManu\Domain\Rating\RatingRepositoryInterface;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\instance_of;

#[OA\Tag('Food Rating')]
final readonly class RatingCrudController
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private RatingRepositoryInterface $ratingRepository,
        private FoodRepositoryInterface $foodRepository,
    ) {
    }

    #[OA\Response(
        response: 200,
        description: 'Returns a personal food rating',
        content: new Model(type: RatingResponse::class)
    )]
    #[Route(path: '/{foodId}', name: '_personal-rating', methods: ['GET'], format: 'json')]
    public function getPersonalRating(
        Uuid $foodId,
        UserInterface $user,
    ): JsonResponse {
        $domainUser = instance_of(User::class)->coerce($user);

        try {
            $food = $this->foodRepository->get($foodId);
        } catch (NotFoundException $e) {
            throw new UnprocessableEntityHttpException('Food does not exist.', $e, 422);
        }

        $rating = $this->ratingRepository->getByUserAndFood($domainUser, $food);

        return new JsonResponse(RatingResponse::fromEntity($rating), 200);
    }

    #[OA\Response(
        response: 200,
        description: 'Returns the created food rating',
        content: new Model(type: RatingResponse::class)
    )]
    #[Route(path: '/', name: '_create', methods: ['POST'], format: 'json')]
    public function create(
        #[MapRequestPayload(acceptFormat: 'json')] CreateRatingRequest $requestPayload,
        UserInterface $user,
    ): JsonResponse {
        $domainUser = instance_of(User::class)->coerce($user);
        $command = $requestPayload->toDomainCommand($domainUser);

        $this->messageBus->dispatch($command);

        $rating = $this->ratingRepository->get($command->id);

        return new JsonResponse(RatingResponse::fromEntity($rating), 200);
    }

    #[Route(path: '/{id}', methods: ['PUT'], format: 'json')]
    public function update(
        #[MapRequestPayload(acceptFormat: 'json')] UpdateRatingRequest $requestPayload,
        Uuid $id,
        UserInterface $user,
    ): JsonResponse {
        $domainUser = instance_of(User::class)->coerce($user);
        $command = $requestPayload->toDomainCommand($domainUser, $id);

        $this->messageBus->dispatch($command);

        $rating = $this->ratingRepository->get($command->id);

        return new JsonResponse(RatingResponse::fromEntity($rating), 200);
    }
}
