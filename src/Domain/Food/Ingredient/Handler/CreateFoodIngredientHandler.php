<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food\Ingredient\Handler;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Veliu\RateManu\Domain\Food\FoodRepositoryInterface;
use Veliu\RateManu\Domain\Food\Ingredient\Command\CreateFoodIngredient;
use Veliu\RateManu\Domain\Food\Ingredient\Event\FoodIngredientCreated;
use Veliu\RateManu\Domain\Food\Ingredient\FoodIngredient;
use Veliu\RateManu\Domain\Food\Ingredient\FoodIngredientRepositoryInterface;
use Veliu\RateManu\Domain\Ingredient\IngredientRepositoryInterface;

#[AsMessageHandler]
final readonly class CreateFoodIngredientHandler
{
    public function __construct(
        private FoodIngredientRepositoryInterface $repository,
        private FoodRepositoryInterface $foodRepository,
        private IngredientRepositoryInterface $ingredientRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(CreateFoodIngredient $command): void
    {
        $food = $this->foodRepository->get($command->foodId);
        $ingredient = $this->ingredientRepository->get($command->ingredientId);

        $foodIngredient = new FoodIngredient(
            $command->id,
            $food,
            $ingredient,
            $command->unit ?? $ingredient->defaultUnit,
            $command->amount,
        );

        $this->repository->upsert($foodIngredient);

        $this->eventDispatcher->dispatch(new FoodIngredientCreated($command->id));
    }
}
