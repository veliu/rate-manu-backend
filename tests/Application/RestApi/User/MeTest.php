<?php

declare(strict_types=1);

namespace Veliu\RateManu\Tests\Application\RestApi\User;

use Veliu\RateManu\Tests\Application\RestApi\ApplicationTestCase;

use function PHPUnit\Framework\assertEquals;
use function Psl\Json\decode;

final class MeTest extends ApplicationTestCase
{
    public function testUpdate(): void
    {
        $client = $this->createAuthenticatedClient('dummy@example.test', 'MySuperscret!1');

        $client->request('GET', '/api/user/me');
        $getResponse = $client->getResponse();
        self::assertEquals(200, $getResponse->getStatusCode());

        $getContent = $getResponse->getContent();
        self::assertNotEmpty($getContent);
        self::assertJson($getContent);
        $getData = decode($getContent);
        self::assertIsArray($getData);

        self::assertArrayHasKey('name', $getData);
        self::assertNull($getData['name']);

        $newName = 'Dexter';

        $client->jsonRequest('PUT', '/api/user/me', ['name' => $newName]);
        $putResponse = $client->getResponse();
        self::assertEquals(200, $putResponse->getStatusCode());

        $putContent = $putResponse->getContent();
        self::assertNotEmpty($putContent);
        self::assertJson($putContent);
        $putData = decode($putContent);
        self::assertIsArray($putData);

        self::assertArrayHasKey('name', $putData);
        assertEquals($newName, $putData['name']);
    }
}
