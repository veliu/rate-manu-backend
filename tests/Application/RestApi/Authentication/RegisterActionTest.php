<?php

declare(strict_types=1);

namespace Veliu\RateManu\Tests\Application\RestApi\Authentication;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function PHPUnit\Framework\assertEquals;
use function Psl\Json\decode;
use function Psl\Type\non_empty_string;
use function Psl\Type\shape;

final class RegisterActionTest extends WebTestCase
{
    public function testRegisterSuccessfully(): void
    {
        $client = static::createClient();

        $user = 'dummy@example.test';
        $pass = '1$SUPERsecret';

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/authentication/register',
            parameters: ['email' => $user, 'password' => $pass]
        );

        $response = $client->getResponse();

        self::assertEquals(204, $response->getStatusCode());
        self::assertEmpty($response->getContent());

        $confirmationMail = $this->getMailerMessage()?->toString();

        self::assertNotEmpty($confirmationMail);

        $client->jsonRequest(method: 'POST', uri: '/api/login_check', parameters: [
            'username' => $user,
            'password' => $pass,
        ]);

        $response = $client->getResponse();

        assertEquals(401, $response->getStatusCode());

        $position = strrpos('?token=3D'.$confirmationMail, '?token=3D');
        self::assertIsInt($position);
        $token = substr($confirmationMail, $position);
        $token = str_replace("=\r\n", '', $token);

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/authentication/confirm-registration',
            parameters: ['token' => $token],
        );

        $response = $client->getResponse();
        assertEquals(204, $response->getStatusCode());

        $client->jsonRequest(method: 'POST', uri: '/api/login_check', parameters: [
            'username' => $user,
            'password' => $pass,
        ]);

        $response = $client->getResponse();

        assertEquals(200, $response->getStatusCode());
        $content = $response->getContent();
        self::assertIsString($content);
        self::assertJson($content);

        $content = decode($content);

        self::assertTrue(shape([
            'token' => non_empty_string(),
            'refresh_token' => non_empty_string(),
        ])->matches($content));
    }
}
