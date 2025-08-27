<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Ingredient\Handler;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Veliu\RateManu\Domain\Ingredient\Command\CreateIngredient;
use Veliu\RateManu\Domain\Ingredient\Event\IngredientCreated;
use Veliu\RateManu\Domain\Ingredient\Ingredient;
use Veliu\RateManu\Domain\Ingredient\IngredientRepositoryInterface;

#[AsMessageHandler]
final readonly class CreateIngredientHandler
{
    public function __construct(
        private IngredientRepositoryInterface $ingredientRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(CreateIngredient $command): void
    {
        $this->ingredientRepository->upsert(new Ingredient(
            $command->id,
            $command->name,
            $command->defaultUnit,
            $command->user,
            $command->group
        ));

        $this->eventDispatcher->dispatch(new IngredientCreated($command->id));
    }
}
