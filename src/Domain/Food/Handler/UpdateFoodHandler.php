<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Veliu\RateManu\Domain\Food\Command\UpdateFood;
use Veliu\RateManu\Domain\Food\FoodRepositoryInterface;

#[AsMessageHandler]
final readonly class UpdateFoodHandler
{
    public function __construct(
        private FoodRepositoryInterface $foodRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(UpdateFood $command): void
    {
        $food = $this->foodRepository->get($command->id);

        $food->name = $command->name;
        $food->description = $command->description;

        $this->entityManager->persist($food);
        $this->entityManager->flush();
    }
}
