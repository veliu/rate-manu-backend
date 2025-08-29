<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\Ingredient;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Application\Request\CreateIngredientRequest;
use Veliu\RateManu\Application\Request\SearchQueryString;
use Veliu\RateManu\Application\Response\IngredientCollectionResponse;
use Veliu\RateManu\Application\Response\IngredientResponse;
use Veliu\RateManu\Domain\Ingredient\IngredientRepositoryInterface;
use Veliu\RateManu\Domain\SearchCriteria;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\instance_of;

#[OA\Tag('Ingredient')]
#[Route(name: 'ingredient')]
final readonly class IngredientCrudController
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private IngredientRepositoryInterface $repository,
    ) {
    }

    #[Route(name: '_search', methods: ['GET'], format: 'json')]
    #[OA\Response(
        response: 200,
        description: 'Returns food search result',
        content: new Model(type: IngredientCollectionResponse::class)
    )]
    public function search(
        #[MapQueryString(validationFailedStatusCode: 422)] ?SearchQueryString $searchQueryString,
        UserInterface $securityUser,
    ): JsonResponse {
        $user = instance_of(User::class)->coerce($securityUser);

        $searchCriteria = $searchQueryString?->toSearchCriteria($user) ?? new SearchCriteria($user->id);

        return new JsonResponse(
            IngredientCollectionResponse::fromDomainCollection(
                $this->repository->search($searchCriteria),
            )
        );
    }

    #[Route(path: '/{id}', name: '_get', methods: ['GET'])]
    #[OA\Parameter(
        name: 'id',
        description: 'The ID of the ingredient',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', format: 'uuid')
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns food',
        content: new Model(type: IngredientResponse::class)
    )]
    #[OA\Response(response: 404, description: 'Ingredient does not exist')]
    public function get(Uuid $id): JsonResponse
    {
        return new JsonResponse(
            IngredientResponse::fromEntity(
                $this->repository->get($id),
            )
        );
    }

    #[Route(name: '_create', methods: ['POST'], format: 'json')]
    #[OA\Response(
        response: 200,
        description: 'Returns food',
        content: new Model(type: IngredientResponse::class)
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation Error',
    )]
    public function create(
        #[MapRequestPayload(acceptFormat: 'json')] CreateIngredientRequest $requestPayload,
        UserInterface $user,
    ): JsonResponse {
        $user = instance_of(User::class)->coerce($user);
        $command = $requestPayload->toDomainCommand($user);

        $this->messageBus->dispatch($command);

        $food = $this->repository->get($command->id);

        return new JsonResponse(IngredientResponse::fromEntity($food), 200);
    }
}
