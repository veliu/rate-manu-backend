<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food\Handler;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Veliu\RateManu\Domain\Food\Command\CreateFood;
use Veliu\RateManu\Domain\Food\Food;
use Veliu\RateManu\Domain\Food\FoodRepositoryInterface;

#[AsMessageHandler]
final readonly class CreateFoodHandler
{
    public function __construct(
        private FoodRepositoryInterface $foodRepository,
    ) {
    }

    public function __invoke(CreateFood $command): void
    {
        $food = new Food(
            $command->uuid,
            $command->name,
            $command->description,
            $command->group,
            $command->user,
        );

        $this->foodRepository->create($food);
    }
}
