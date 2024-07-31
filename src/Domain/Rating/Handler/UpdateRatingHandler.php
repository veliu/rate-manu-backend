<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Rating\Handler;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Veliu\RateManu\Domain\Exception\NotAllowedException;
use Veliu\RateManu\Domain\Rating\Command\UpdateRating;
use Veliu\RateManu\Domain\Rating\RatingRepositoryInterface;

#[AsMessageHandler]
final readonly class UpdateRatingHandler
{
    public function __construct(
        private RatingRepositoryInterface $ratingRepository,
    ) {
    }

    public function __invoke(UpdateRating $command): void
    {
        $foodRating = $this->ratingRepository->get($command->id);

        if (!$foodRating->createdBy->id->equals($command->userId)) {
            throw new NotAllowedException('Only the original author can change the rating');
        }

        $foodRating->updateRating($command->rating);

        $this->ratingRepository->save($foodRating);
    }
}
