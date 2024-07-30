<?php

declare(strict_types=1);

namespace Veliu\RateManu\Tests\Application\RestApi;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEmpty;
use function Psl\Json\decode;

class ApplicationTestCase extends WebTestCase
{
    protected function createAuthenticatedClient(string $username = 'user', string $password = 'password'): KernelBrowser
    {
        $client = static::createClient();

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/authentication/register',
            parameters: ['email' => $username, 'password' => $password]
        );

        assertEquals(204, $client->getResponse()->getStatusCode());

        $confirmationMail = $this->getMailerMessage()?->toString();
        self::assertNotEmpty($confirmationMail);

        $position = strrpos('?token=3D'.$confirmationMail, '?token=3D');
        self::assertIsInt($position);
        $token = substr($confirmationMail, $position);
        $token = str_replace("=\r\n", '', $token);

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/authentication/confirm-registration?token='.$token,
        );

        assertEquals(204, $client->getResponse()->getStatusCode());

        $client->jsonRequest(
            'POST',
            '/api/login_check',
            [
                'username' => $username,
                'password' => $password,
            ]
        );

        $content = $client->getResponse()->getContent();

        self::assertNotEmpty($content);

        $data = decode($content);

        self::assertIsArray($data);

        $token = $data['token'] ?? null;

        assertNotEmpty($token);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }
}
