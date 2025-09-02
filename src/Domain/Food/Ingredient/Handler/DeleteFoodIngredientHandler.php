<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food\Ingredient\Handler;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Veliu\RateManu\Domain\Food\Ingredient\Command\DeleteFoodIngredient;
use Veliu\RateManu\Domain\Food\Ingredient\Event\FoodIngredientDeleted;
use Veliu\RateManu\Domain\Food\Ingredient\FoodIngredientRepositoryInterface;

#[AsMessageHandler]
final readonly class DeleteFoodIngredientHandler
{
    public function __construct(
        private FoodIngredientRepositoryInterface $repository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(DeleteFoodIngredient $command): void
    {
        if ($entity = $this->repository->findByFoodAndIngredient($command->foodId, $command->ingredientId)) {
            $this->repository->delete($entity);
            $this->eventDispatcher->dispatch(new FoodIngredientDeleted($entity->id));
        }
    }
}
