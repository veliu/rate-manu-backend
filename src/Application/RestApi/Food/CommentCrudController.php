<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\Food;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Application\Request\CreateCommentRequest;
use Veliu\RateManu\Application\Response\CommentCollectionResponse;
use Veliu\RateManu\Application\Response\CommentResponse;
use Veliu\RateManu\Domain\Comment\CommentRepositoryInterface;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\instance_of;

#[OA\Tag('Food Comments')]
#[Route(name: 'food_comments')]
final readonly class CommentCrudController
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private CommentRepositoryInterface $commentRepository,
    ) {
    }

    #[Route(path: '/{id}/comment', name: '_create', methods: ['POST'], format: 'json')]
    #[OA\Response(
        response: 200,
        description: 'Returns food',
        content: new Model(type: CommentResponse::class)
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation Error',
    )]
    public function create(
        #[MapRequestPayload(acceptFormat: 'json')] CreateCommentRequest $requestPayload,
        UserInterface $user,
        Uuid $id,
    ): JsonResponse {
        $user = instance_of(User::class)->coerce($user);

        $command = $requestPayload->toDomainCommand($user, $id);

        $this->messageBus->dispatch($command);

        $comment = $this->commentRepository->get($command->id);

        return new JsonResponse(CommentResponse::fromEntity($comment), 200);
    }

    #[Route(path: '/{id}/comment', name: '_get', methods: ['GET'], format: 'json')]
    #[OA\Response(
        response: 200,
        description: 'Returns food',
        content: new Model(type: CommentCollectionResponse::class)
    )]
    #[OA\Response(
        response: 422,
        description: 'Validation Error',
    )]
    public function getAll(
        Uuid $id,
    ): JsonResponse {
        $comments = $this->commentRepository->getForFood($id);

        return new JsonResponse(CommentCollectionResponse::fromDomainCollection($comments), 200);
    }
}
