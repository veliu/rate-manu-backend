<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\RestApi\Authentication;

use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Attribute\Route;

#[OA\Tag('Authentication')]
#[OA\RequestBody(
    description: 'User credentials',
    required: true,
    content: new OA\JsonContent(
        required: ['username', 'password'],
        properties: [
            new OA\Property(property: 'username', type: 'string', format: 'email'),
            new OA\Property(property: 'password', type: 'string'),
        ],
        type: 'object',
    )
)]
#[OA\Response(
    response: 204,
    description: 'Successfully confirmed user registration.',
)]
#[Route(path: '/api/login_check', name: 'login_check', methods: ['POST'], format: 'json')]
final readonly class LoginCheckAction
{
    public function __invoke()
    {
        throw new \LogicException('Should not be called. This route is only used for api documentation');
    }
}
