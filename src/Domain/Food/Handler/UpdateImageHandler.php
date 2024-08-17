<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\String\Slugger\SluggerInterface;
use Veliu\RateManu\Domain\Food\Command\UpdateImage;
use Veliu\RateManu\Domain\Food\FoodRepositoryInterface;

#[AsMessageHandler]
final readonly class UpdateImageHandler
{
    public function __construct(
        private FoodRepositoryInterface $foodRepository,
        private SluggerInterface $slugger,
        private EntityManagerInterface $entityManager,
        #[Autowire('%kernel.project_dir%/public/uploads/food')] private string $foodImageDirectory,
    ) {
    }

    public function __invoke(UpdateImage $command): void
    {
        $food = $this->foodRepository->get($command->foodId);
        $image = $command->image;

        $originalFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFileName);
        $newFilename = sprintf(
            '/uploads/food/%s-%s.%s',
            $safeFilename,
            uniqid(),
            $image->guessExtension()
        );

        $image->move($this->foodImageDirectory, $newFilename);
        $food->setImage($newFilename);

        $this->entityManager->persist($food);
        $this->entityManager->flush();
    }
}
