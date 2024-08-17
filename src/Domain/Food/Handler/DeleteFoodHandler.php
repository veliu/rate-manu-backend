<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food\Handler;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Veliu\RateManu\Domain\File\FileStorageInterface;
use Veliu\RateManu\Domain\Food\Command\DeleteFood;
use Veliu\RateManu\Domain\Food\FoodRepositoryInterface;

#[AsMessageHandler]
final readonly class DeleteFoodHandler
{
    public function __construct(
        private FoodRepositoryInterface $foodRepository,
        private FileStorageInterface $fileStorage,
    ) {
    }

    public function __invoke(DeleteFood $command): void
    {
        $food = $this->foodRepository->get($command->id);

        if ($image = $food->getImage()) {
            $this->fileStorage->deleteFile($image);
        }

        $this->foodRepository->delete($food);
    }
}
