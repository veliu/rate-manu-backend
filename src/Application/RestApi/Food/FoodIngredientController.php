<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\Food;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Application\Request\CreateFoodIngredientsRequest;
use Veliu\RateManu\Application\Response\FoodIngredientCollectionResponse;
use Veliu\RateManu\Domain\Food\FoodRepositoryInterface;
use Veliu\RateManu\Domain\Food\Ingredient\Command\DeleteFoodIngredient;
use Veliu\RateManu\Domain\Food\Ingredient\FoodIngredientRepositoryInterface;
use Veliu\RateManu\Domain\Ingredient\IngredientRepositoryInterface;

#[OA\Tag('Food Ingredients')]
#[Route(name: 'food_ingredients')]
final readonly class FoodIngredientController
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private FoodIngredientRepositoryInterface $repository,
        private FoodRepositoryInterface $foodRepository,
        private IngredientRepositoryInterface $ingredientRepository,
    ) {
    }

    #[Route(path: '/{foodId}/ingredients', name: '_get', methods: ['GET'])]
    #[OA\Parameter(
        name: 'foodId',
        description: 'The ID of the food',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', format: 'uuid')
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns food ingredients',
        content: new Model(type: FoodIngredientCollectionResponse::class)
    )]
    #[OA\Response(response: 404, description: 'Food does not exist')]
    public function get(Uuid $foodId): JsonResponse
    {
        $ingredientCollection = $this->repository->findByFood($foodId);

        return new JsonResponse(FoodIngredientCollectionResponse::fromEntityCollection($foodId, $ingredientCollection), 200);
    }

    #[Route(path: '/{foodId}/ingredients', name: '_create', methods: ['POST'])]
    #[OA\Parameter(
        name: 'foodId',
        description: 'The ID of the food',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', format: 'uuid')
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns food ingredients',
        content: new Model(type: FoodIngredientCollectionResponse::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Food does not exist')
    ]
    #[OA\Response(
        response: 422,
        description: 'Validation Error',
    )]
    public function add(
        Uuid $foodId,
        #[MapRequestPayload(acceptFormat: 'json')] CreateFoodIngredientsRequest $requestPayload,
    ): JsonResponse {
        $this->foodRepository->get($foodId);

        $commands = $requestPayload->toDomainCommands($foodId);

        foreach ($commands as $command) {
            $this->messageBus->dispatch($command);
        }

        $ingredientCollection = $this->repository->findByFood($foodId);

        return new JsonResponse(FoodIngredientCollectionResponse::fromEntityCollection($foodId, $ingredientCollection), 200);
    }

    #[Route(path: '/{foodId}/ingredients{ingredientId}', name: '_delete', methods: ['DELETE'])]
    #[OA\Parameter(
        name: 'foodId',
        description: 'The ID of the food',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', format: 'uuid')
    )]
    #[OA\Parameter(
        name: 'ingredientId',
        description: 'The ID of the ingredient',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', format: 'uuid')
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns food ingredients',
        content: new Model(type: FoodIngredientCollectionResponse::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Not found. Food or ingredient does not exist')
    ]
    public function delete(Uuid $foodId, Uuid $ingredientId): JsonResponse
    {
        $this->foodRepository->get($foodId);
        $this->ingredientRepository->get($ingredientId);

        $this->messageBus->dispatch(new DeleteFoodIngredient($foodId, $ingredientId));

        $ingredientCollection = $this->repository->findByFood($foodId);

        return new JsonResponse(FoodIngredientCollectionResponse::fromEntityCollection($foodId, $ingredientCollection), 200);
    }
}
