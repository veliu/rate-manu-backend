<?php

declare(strict_types=1);

namespace Veliu\RateManu\Tests\Application\RestApi\Rating;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Tests\Application\RestApi\ApplicationTestCase;

use function Psl\Json\decode;

final class RatingTest extends ApplicationTestCase
{
    public function testCreate(): void
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

        self::assertEquals(200, $client->getResponse()->getStatusCode());

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/rating/',
            parameters: [
                'food' => $foodId->toString(),
                'rating' => 5,
            ]
        );

        $response = $client->getResponse();

        self::assertEquals(200, $response->getStatusCode());

        $content = $response->getContent();

        self::assertIsString($content);
        self::assertNotEmpty($content);
        self::assertIsString($content);

        $responseBody = decode($content);

        self::assertIsArray($responseBody);
        self::assertNotEmpty($responseBody);

        self::assertIsString($responseBody['id']);
        self::assertIsString($responseBody['food']);
        self::assertIsString($responseBody['createdBy']);
        self::assertIsString($responseBody['createdAt']);
        self::assertIsString($responseBody['updatedAt']);
        self::assertIsInt($responseBody['rating']);

        self::assertTrue(Uuid::isValid($responseBody['id']));
        self::assertTrue(Uuid::isValid($responseBody['createdBy']));
        self::assertTrue(Uuid::isValid($responseBody['food']));
        self::assertEquals($foodId->toString(), $responseBody['food']);
    }
}
