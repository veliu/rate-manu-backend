<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\Food;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Application\Request\CreateFoodRequest;
use Veliu\RateManu\Application\Response\FoodCollectionResponse;
use Veliu\RateManu\Application\Response\FoodResponse;
use Veliu\RateManu\Domain\Food\Command\DeleteFood;
use Veliu\RateManu\Domain\Food\FoodRepositoryInterface;
use Veliu\RateManu\Domain\User\GroupRelation;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\instance_of;

#[OA\Tag('Food')]
#[Route(name: 'food')]
final readonly class FoodCrudController
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private FoodRepositoryInterface $foodRepository,
        #[Autowire('%appUrl%')] private string $appUrl,
    ) {
    }

    #[Route(path: '/{id}', name: '_read', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns food',
        content: new Model(type: FoodResponse::class)
    )]
    #[OA\Response(response: 404, description: 'Food does not exist')]
    public function get(Uuid $id, UserInterface $securityUser): JsonResponse
    {
        $user = instance_of(User::class)->coerce($securityUser);

        return new JsonResponse(
            FoodResponse::fromEntity(
                $this->foodRepository->get($id),
                $user,
                $this->appUrl,
            )
        );
    }

    #[Route(path: '/{id}', name: '_delete', methods: ['DELETE'])]
    #[OA\Response(response: 204, description: 'Food deleted')]
    #[OA\Response(response: 404, description: 'Food does not exist')]
    public function delete(Uuid $id): JsonResponse
    {
        $this->messageBus->dispatch(new DeleteFood($id));

        return new JsonResponse(null, 204);
    }

    #[Route(path: '/', name: '_search', methods: ['GET'], format: 'json')]
    #[OA\Response(
        response: 200,
        description: 'Returns food search result',
        content: new Model(type: FoodCollectionResponse::class)
    )]
    public function search(UserInterface $securityUser): JsonResponse
    {
        $user = instance_of(User::class)->coerce($securityUser);

        return new JsonResponse(
            FoodCollectionResponse::fromDomainCollection(
                $this->foodRepository->findByUser($user),
                $user,
                $this->appUrl,
            )
        );
    }

    #[Route(path: '/', name: '_create', methods: ['POST'], format: 'json')]
    #[OA\Response(
        response: 200,
        description: 'Returns food',
        content: new Model(type: FoodResponse::class)
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation Error',
    )]
    public function create(
        #[MapRequestPayload(acceptFormat: 'json')] CreateFoodRequest $requestPayload,
        UserInterface $user,
    ): JsonResponse {
        $user = instance_of(User::class)->coerce($user);
        $groupRelation = instance_of(GroupRelation::class)->coerce($user->getGroupRelations()->first());

        $command = $requestPayload->toDomainCommand($groupRelation->group, $user);

        $this->messageBus->dispatch($command);

        $food = $this->foodRepository->get($command->uuid);

        return new JsonResponse(FoodResponse::fromEntity($food, $user, $this->appUrl), 200);
    }
}
