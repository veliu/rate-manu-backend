<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Rating\Handler;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Veliu\RateManu\Domain\Food\FoodRepositoryInterface;
use Veliu\RateManu\Domain\Rating\Command\CreateRating;
use Veliu\RateManu\Domain\Rating\Rating;
use Veliu\RateManu\Domain\Rating\RatingRepositoryInterface;
use Veliu\RateManu\Domain\User\UserRepositoryInterface;

#[AsMessageHandler]
final readonly class CreateRatingHandler
{
    public function __construct(
        private RatingRepositoryInterface $ratingRepository,
        private UserRepositoryInterface $userRepository,
        private FoodRepositoryInterface $foodRepository,
    ) {
    }

    public function __invoke(CreateRating $command): void
    {
        $user = $this->userRepository->get($command->userId);
        $food = $this->foodRepository->get($command->foodId);

        $rating = new Rating($command->id, $user, $food, $command->rating);

        $this->ratingRepository->create($rating);
    }
}
