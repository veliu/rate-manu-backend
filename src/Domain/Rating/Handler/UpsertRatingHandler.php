<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Rating\Handler;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Exception\NotFoundException;
use Veliu\RateManu\Domain\Food\FoodRepositoryInterface;
use Veliu\RateManu\Domain\Rating\Command\UpsertRating;
use Veliu\RateManu\Domain\Rating\Rating;
use Veliu\RateManu\Domain\Rating\RatingRepositoryInterface;
use Veliu\RateManu\Domain\User\UserRepositoryInterface;

#[AsMessageHandler]
final readonly class UpsertRatingHandler
{
    public function __construct(
        private RatingRepositoryInterface $ratingRepository,
        private UserRepositoryInterface $userRepository,
        private FoodRepositoryInterface $foodRepository,
    ) {
    }

    public function __invoke(UpsertRating $command): void
    {
        $user = $this->userRepository->get($command->userId);
        $food = $this->foodRepository->get($command->foodId);

        try {
            $rating = $this->ratingRepository->getByUserAndFood($user, $food);
            $rating->updateRating($command->rating);
        } catch (NotFoundException) {
            $rating = new Rating(Uuid::v4(), $user, $food, $command->rating);
        }

        $this->ratingRepository->save($rating);
    }
}
