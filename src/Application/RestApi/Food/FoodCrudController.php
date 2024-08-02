<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\Food;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Veliu\RateManu\Application\Request\CreateFoodRequest;
use Veliu\RateManu\Application\Response\FoodResponse;
use Veliu\RateManu\Domain\Food\FoodRepositoryInterface;
use Veliu\RateManu\Domain\Group\Group;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\instance_of;

final readonly class FoodCrudController
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private FoodRepositoryInterface $foodRepository,
    ) {
    }

    #[Route(path: '/', methods: ['POST'], format: 'json')]
    public function create(
        #[MapRequestPayload(acceptFormat: 'json')] CreateFoodRequest $requestPayload,
        UserInterface $user,
    ): JsonResponse {
        $user = instance_of(User::class)->coerce($user);
        $group = instance_of(Group::class)->coerce($user->getGroups()->first());

        $command = $requestPayload->toDomainCommand($group, $user);

        $this->messageBus->dispatch($command);

        $food = $this->foodRepository->get($command->uuid);

        return new JsonResponse(FoodResponse::fromEntity($food), 200);
    }
}
