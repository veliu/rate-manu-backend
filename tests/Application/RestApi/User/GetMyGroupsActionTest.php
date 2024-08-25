<?php

declare(strict_types=1);

namespace Veliu\RateManu\Tests\Application\RestApi\User;

use Veliu\RateManu\Tests\Application\RestApi\ApplicationTestCase;

use function Psl\Json\decode;
use function Psl\Type\literal_scalar;
use function Psl\Type\non_empty_string;
use function Psl\Type\non_empty_vec;
use function Psl\Type\nullable;
use function Psl\Type\shape;

final class GetMyGroupsActionTest extends ApplicationTestCase
{
    public function testInvite(): void
    {
        $email = 'dummy@example.test';

        $client = $this->createAuthenticatedClient($email, 'MySuperscret!1');

        $client->jsonRequest('GET', '/api/user/my-groups');

        $response = $client->getResponse();
        self::assertEquals(200, $response->getStatusCode());

        $content = $response->getContent();
        self::assertNotEmpty($content);
        self::assertJson($content);

        $dataResponse = decode($content);

        $expectedType = shape([
            'count' => literal_scalar(1),
            'items' => non_empty_vec(shape([
                'id' => non_empty_string(),
                'name' => literal_scalar('First Group'),
                'role' => literal_scalar('owner'),
                'members' => non_empty_vec(shape([
                    'id' => non_empty_string(),
                    'email' => literal_scalar($email),
                    'status' => literal_scalar('active'),
                    'name' => nullable(non_empty_string()),
                ])),
            ])),
        ]);

        self::assertTrue($expectedType->matches($dataResponse));
    }
}
