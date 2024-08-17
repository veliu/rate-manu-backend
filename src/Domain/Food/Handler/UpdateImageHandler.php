<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Veliu\RateManu\Domain\File\FileStorageInterface;
use Veliu\RateManu\Domain\Food\Command\UpdateImage;
use Veliu\RateManu\Domain\Food\FoodRepositoryInterface;

#[AsMessageHandler]
final readonly class UpdateImageHandler
{
    public function __construct(
        private FoodRepositoryInterface $foodRepository,
        private EntityManagerInterface $entityManager,
        private FileStorageInterface $fileStorage,
    ) {
    }

    public function __invoke(UpdateImage $command): void
    {
        $food = $this->foodRepository->get($command->foodId);
        $fileName = $this->fileStorage->createFile($command->image);
        $food->setImage($fileName);

        $this->entityManager->persist($food);
        $this->entityManager->flush();
    }
}
