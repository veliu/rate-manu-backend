<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\User;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Veliu\RateManu\Application\Response\UserGroupsResponse;
use Veliu\RateManu\Domain\User\User;

use function Psl\Type\instance_of;

#[OA\Tag('User')]
#[Route(path: '/my-groups', name: 'my-groups', methods: ['GET'], format: 'json')]
final readonly class GetMyGroupsAction
{
    #[OA\Response(
        response: 200,
        description: 'User group details',
        content: new Model(type: UserGroupsResponse::class)
    )]
    public function __invoke(UserInterface $user): JsonResponse
    {
        $domainUser = instance_of(User::class)->coerce($user);
        $response = UserGroupsResponse::fromDomainCollection($domainUser->getGroupRelations());

        return new JsonResponse($response, 200);
    }
}
