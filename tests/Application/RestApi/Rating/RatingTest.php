<?php

declare(strict_types=1);

namespace Veliu\RateManu\Tests\Application\RestApi\Rating;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Tests\Application\RestApi\ApplicationTestCase;

use function PHPUnit\Framework\assertEquals;
use function Psl\Json\decode;
use function Psl\Type\int;
use function Psl\Type\shape;

final class RatingTest extends ApplicationTestCase
{
    public function testUpsert(): void
    {
        $client = $this->createAuthenticatedClient();

        $foodId = Uuid::v4();

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/food/',
            parameters: [
                'id' => $foodId->toString(),
                'name' => 'TK Pizza',
            ]
        );

        $response = $client->getResponse();

        self::assertEquals(200, $response->getStatusCode());

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/food-rating/',
            parameters: [
                'food' => $foodId->toString(),
                'rating' => 5,
            ]
        );

        $response = $client->getResponse();
        self::assertEquals(200, $response->getStatusCode());

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/food-rating/',
            parameters: [
                'food' => $foodId->toString(),
                'rating' => 5,
            ]
        );

        $response = $client->getResponse();

        self::assertEquals(200, $response->getStatusCode());

        $client->jsonRequest(
            method: 'GET',
            uri: '/api/food/'.$foodId->toString(),
        );

        $response = $client->getResponse();
        self::assertEquals(200, $response->getStatusCode());
        self::assertIsString($response->getContent());
        self::assertJson($response->getContent());
        $data = decode($response->getContent());

        self::assertTrue(shape([
            'averageRating' => int(),
        ], true)->matches($data));

        assertEquals(5, $data['averageRating'] ?? null);
    }
}
