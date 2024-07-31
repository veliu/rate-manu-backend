<?php

declare(strict_types=1);

namespace Veliu\RateManu\Tests\Application\RestApi\Food;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Tests\Application\RestApi\ApplicationTestCase;

use function Psl\Json\decode;
use function Psl\Type\non_empty_string;
use function Psl\Type\nullable;
use function Psl\Type\shape;

final class FoodTest extends ApplicationTestCase
{
    public function testCreate(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/food/',
            parameters: [
                'id' => Uuid::v4()->toString(),
                'name' => 'TK Pizza',
            ]
        );

        $response = $client->getResponse();
        self::assertEquals(200, $response->getStatusCode());
        self::assertIsString($response->getContent());
        self::assertJson($response->getContent());
        $data = decode($response->getContent());

        self::assertTrue(shape([
            'id' => non_empty_string(),
            'name' => non_empty_string(),
            'description' => nullable(non_empty_string()),
            'author' => non_empty_string(),
            'group' => non_empty_string(),
            'createdAt' => non_empty_string(),
            'updatedAt' => non_empty_string(),
        ])->matches($data));

        self::assertEquals('TK Pizza', $data['name']);
        self::assertNull($data['description']);
        self::assertTrue(Uuid::isValid($data['id']));
        self::assertTrue(Uuid::isValid($data['author']));
        self::assertTrue(Uuid::isValid($data['group']));
    }
}
