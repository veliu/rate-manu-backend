<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Comment\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Veliu\RateManu\Domain\Comment\Command\CreateComment;
use Veliu\RateManu\Domain\Comment\Comment;
use Veliu\RateManu\Domain\Food\FoodRepositoryInterface;
use Veliu\RateManu\Domain\User\UserRepositoryInterface;

#[AsMessageHandler]
final readonly class CreateCommentHandler
{
    public function __construct(
        private EntityManagerInterface $em,
        private FoodRepositoryInterface $foodRepository,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(CreateComment $command): void
    {
        $user = $this->userRepository->get($command->userId);
        $food = $this->foodRepository->get($command->foodId);

        $comment = new Comment($command->id, $user, $food, $command->comment);

        $this->em->persist($comment);
        $this->em->flush();
    }
}
