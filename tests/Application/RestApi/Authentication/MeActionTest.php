<?php

declare(strict_types=1);

namespace Veliu\RateManu\Tests\Application\RestApi\Authentication;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\User\Status;
use Veliu\RateManu\Tests\Application\RestApi\ApplicationTestCase;

use function Psl\Json\decode;
use function Psl\Type\non_empty_string;
use function Psl\Type\non_empty_vec;
use function Psl\Type\shape;

final class MeActionTest extends ApplicationTestCase
{
    public function testMeResponse(): void
    {
        $userEmail = 'dummy@exmaple.test';
        $client = self::createAuthenticatedClient($userEmail, 'superSECRET1!');

        $client->jsonRequest('GET', '/api/user/me');

        $response = $client->getResponse()->getContent();

        self::assertNotEmpty($response);
        self::assertJson($response);

        $body = decode($response);

        self::assertTrue(shape([
            'id' => non_empty_string(),
            'email' => non_empty_string(),
            'status' => non_empty_string(),
            'groups' => non_empty_vec(non_empty_string()),
        ])->matches($body));

        self::assertIsString($body['id']);
        self::assertNotEmpty($body['id']);
        self::assertTrue(Uuid::isValid($body['id']));
        self::assertEquals($userEmail, $body['email']);
        self::assertEquals(Status::ACTIVE->value, $body['status']);
        self::assertIsArray($body['groups']);
        self::assertNotEmpty($body['groups']);

        foreach ($body['groups'] as $groupId) {
            self::assertIsString($groupId);
            self::assertNotEmpty($groupId);
            self::assertTrue(Uuid::isValid($groupId));
        }
    }
}
