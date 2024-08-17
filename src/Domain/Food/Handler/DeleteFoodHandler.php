<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food\Handler;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Veliu\RateManu\Domain\Food\Command\DeleteFood;
use Veliu\RateManu\Domain\Food\FoodRepositoryInterface;

#[AsMessageHandler]
final readonly class DeleteFoodHandler
{
    public function __construct(
        private FoodRepositoryInterface $foodRepository,
    ) {
    }

    public function __invoke(DeleteFood $command): void
    {
        $food = $this->foodRepository->get($command->id);
        $image = $food->getImage();
        $fileSystem = new Filesystem();

        if ($image && $fileSystem->exists($image)) {
            $fileSystem->remove($image);
        }

        $this->foodRepository->delete($food);
    }
}
