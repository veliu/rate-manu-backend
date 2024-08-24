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
use Veliu\RateManu\Application\Request\UpsertRatingRequest;
use Veliu\RateManu\Application\Response\PersonalRatingResponse;
use Veliu\RateManu\Application\Response\RatingCollectionResponse;
use Veliu\RateManu\Domain\Exception\NotFoundException;
use Veliu\RateManu\Domain\Food\FoodRepositoryInterface;
use Veliu\RateManu\Domain\Rating\RatingRepositoryInterface;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\instance_of;

#[OA\Tag('Food Rating')]
#[Route(name: 'food-rating')]
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
        content: new Model(type: PersonalRatingResponse::class)
    )]
    #[Route(path: '/my/{foodId}', name: '_personal-rating', methods: ['GET'], format: 'json')]
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

        return new JsonResponse(PersonalRatingResponse::fromEntity($rating), 200);
    }

    #[OA\Response(
        response: 200,
        description: 'Returns ratings from all members',
        content: new Model(type: PersonalRatingResponse::class)
    )]
    #[Route(path: '/{foodId}', name: '_ratings', methods: ['GET'], format: 'json')]
    public function getRatings(
        Uuid $foodId,
        UserInterface $user,
    ): JsonResponse {
        $domainUser = instance_of(User::class)->coerce($user);

        try {
            $food = $this->foodRepository->get($foodId);
        } catch (NotFoundException $e) {
            throw new UnprocessableEntityHttpException('Food does not exist.', $e, 422);
        }

        $ratingCollection = $this->ratingRepository->findForAllMembers($domainUser, $food);

        return new JsonResponse(RatingCollectionResponse::fromDomainCollection($ratingCollection), 200);
    }

    #[OA\Response(
        response: 200,
        description: 'Updates a food rating',
        content: new Model(type: PersonalRatingResponse::class)
    )]
    #[Route(path: '/', name: '_upsert', methods: ['POST'], format: 'json')]
    public function upsert(
        #[MapRequestPayload(acceptFormat: 'json')] UpsertRatingRequest $requestPayload,
        UserInterface $user,
    ): JsonResponse {
        $domainUser = instance_of(User::class)->coerce($user);
        $command = $requestPayload->toDomainCommand($domainUser);

        $this->messageBus->dispatch($command);

        $food = $this->foodRepository->get($command->foodId);

        $rating = $this->ratingRepository->getByUserAndFood($domainUser, $food);

        return new JsonResponse(PersonalRatingResponse::fromEntity($rating), 200);
    }
}
