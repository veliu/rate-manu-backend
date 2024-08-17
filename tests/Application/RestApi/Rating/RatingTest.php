<?php

declare(strict_types=1);

namespace Veliu\RateManu\Tests\Application\RestApi\Rating;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Tests\Application\RestApi\ApplicationTestCase;

use function Psl\Json\decode;

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

        self::assertEquals(200, $client->getResponse()->getStatusCode());

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/food-rating/',
            parameters: [
                'food' => $foodId->toString(),
                'rating' => 5,
            ]
        );

        self::assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
