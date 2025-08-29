<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\Authentication;

use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag('Authentication')]
#[OA\RequestBody(
    content: new OA\JsonContent(
        required: ['refresh_token'],
        properties: [
            new OA\Property(property: 'refresh_token', type: 'string'),
        ]
    )
)]
#[OA\Response(
    response: 200,
    description: 'New access tokens.',
    content: new OA\JsonContent(
        required: ['token', 'refresh_token'],
        properties: [
            new OA\Property(property: 'token', type: 'string'),
            new OA\Property(property: 'refresh_token', type: 'string'),
        ],
        type: 'object',
    )
)]
#[Route(path: '/token/refresh', name: 'token_refresh', methods: ['POST'], format: 'json')]
final readonly class RefreshTokenAction
{
    public function __invoke()
    {
        throw new \LogicException('Should not be called. This route is only used for api documentation');
    }
}
