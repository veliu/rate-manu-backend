<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\Food;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Veliu\RateManu\Application\Response\FoodResponse;
use Veliu\RateManu\Domain\Food\Command\UpdateImage;
use Veliu\RateManu\Domain\Food\FoodRepositoryInterface;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\instance_of;

#[OA\Tag('Food Images')]
#[OA\Parameter(
    name: 'id',
    description: 'The ID of the food',
    in: 'path',
    required: true,
    schema: new OA\Schema(type: 'string', format: 'uuid')
)]
#[OA\Response(
    response: 200,
    description: 'Successfully updated food image.',
    content: new Model(type: FoodResponse::class)
)]
#[Route(path: '/{id}/update-image', name: 'food_images_update', methods: ['POST'], format: 'multipart/form-data')]
final readonly class UpdateImageAction
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private FoodRepositoryInterface $foodRepository,
    ) {
    }

    public function __invoke(
        Uuid $id,
        #[MapUploadedFile([new Assert\File(maxSize: '8388608', mimeTypes: ['image/png', 'image/jpeg', 'image/gif'])])] UploadedFile $image,
        UserInterface $authenticatedUser,
    ): JsonResponse {
        $user = instance_of(User::class)->coerce($authenticatedUser);

        $this->messageBus->dispatch(new UpdateImage($id, $image));

        $food = $this->foodRepository->get($id);

        $response = FoodResponse::fromEntity($food, $user);

        return new JsonResponse($response, 200);
    }
}
