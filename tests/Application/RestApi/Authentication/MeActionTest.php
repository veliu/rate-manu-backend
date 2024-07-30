<?php

declare(strict_types=1);

namespace Veliu\RateManu\Tests\Application\RestApi\Authentication;

use Veliu\RateManu\Domain\User\Role;
use Veliu\RateManu\Domain\User\Status;
use Veliu\RateManu\Tests\Application\RestApi\ApplicationTest;

use function PHPUnit\Framework\assertEquals;
use function Psl\Json\decode;
use function Psl\Type\non_empty_string;
use function Psl\Type\non_empty_vec;
use function Psl\Type\shape;

final class MeActionTest extends ApplicationTest
{
    public function testMe(): void
    {
        $userEmail = 'dummy@exmaple.test';
        $client = self::createAuthenticatedClient($userEmail, 'superSECRET1!');

        $client->jsonRequest('GET', '/api/authentication/me');

        $response = $client->getResponse()->getContent();

        self::assertNotEmpty($response);
        self::assertJson($response);

        $body = decode($response);

        self::assertTrue(shape([
            'uuid' => non_empty_string(),
            'email' => non_empty_string(),
            'status' => non_empty_string(),
            'roles' => non_empty_vec(non_empty_string()),
        ])->matches($body));

        self::assertEquals($userEmail, $body['email']);
        assertEquals(Status::ACTIVE->value, $body['status']);
        assertEquals([Role::OWNER->value], $body['roles']);
    }
}
