<?php

declare(strict_types=1);

namespace Veliu\RateManu\Tests\Application\RestApi\User;

use Veliu\RateManu\Tests\Application\RestApi\ApplicationTestCase;

use function PHPUnit\Framework\assertEquals;

final class InviteUserActionTest extends ApplicationTestCase
{
    public function testInvite(): void
    {
        $client = $this->createAuthenticatedClient('dummy@example.test', 'MySuperscret!1');

        $client->jsonRequest('POST', '/api/user/invite/larry@example.test');

        $response = $client->getResponse();

        self::assertEquals(204, $response->getStatusCode());
        self::assertEmpty($response->getContent());

        $confirmationMail = $this->getMailerMessage()?->toString();

        self::assertNotEmpty($confirmationMail);

        $position = strrpos('?token=3D'.$confirmationMail, '?token=3D');
        self::assertIsInt($position);
        $token = substr($confirmationMail, $position);
        $token = str_replace("=\r\n", '', $token);

        self::assertIsString($token);
        self::assertNotEmpty($token);

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/authentication/confirm-registration?token='.$token,
        );

        dump($client->getResponse()->getContent());
        assertEquals(204, $client->getResponse()->getStatusCode());
    }

    public function testFailsUnauthenticated(): void
    {
        $client = self::createClient();

        $client->jsonRequest('POST', '/api/user/invite/larry@example.test');

        self::assertEquals(401, $client->getResponse()->getStatusCode());
    }
}
