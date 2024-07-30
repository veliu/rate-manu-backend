<?php

declare(strict_types=1);

namespace Veliu\RateManu\Tests\Application\RestApi\Authentication;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RegisterActionTest extends WebTestCase
{
    public function testRegisterSuccessfully(): void
    {
        $client = static::createClient();

        // Request a specific page
        $client->jsonRequest(
            method: 'POST',
            uri: '/api/authentication/register',
            parameters: ['email' => 'dummy@example.test', 'password' => '1$SUPERsecret']
        );

        $response = $client->getResponse();

        self::assertEquals(204, $response->getStatusCode());
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
