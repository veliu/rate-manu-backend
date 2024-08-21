<?php

declare(strict_types=1);

namespace Veliu\RateManu\Tests\Application\RestApi\User;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Application\Request\InviteUserRequest;
use Veliu\RateManu\Domain\User\GroupRelation;
use Veliu\RateManu\Domain\User\UserRepositoryInterface;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;
use Veliu\RateManu\Tests\Application\RestApi\ApplicationTestCase;

use function Psl\Type\instance_of;

final class InviteUserActionTest extends ApplicationTestCase
{
    public function testInvite(): void
    {
        $client = $this->createAuthenticatedClient('dummy@example.test', 'MySuperscret!1');

        $userRepository = instance_of(UserRepositoryInterface::class)->coerce($this->getContainer()->get(UserRepositoryInterface::class));

        $user = $userRepository->getByEmail(EmailAddress::fromAny('dummy@example.test'));

        $groupRelation = instance_of(GroupRelation::class)->coerce($user->getGroupRelations()->first());

        $request = new InviteUserRequest('larry@example.test', $groupRelation->group->getId());

        $client->jsonRequest('POST', '/api/user/invite', (array) $request);

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
    }

    public function testInviteFailsWhenGroupNotExists(): void
    {
        $client = $this->createAuthenticatedClient('dummy@example.test', 'MySuperscret!1');

        $request = new InviteUserRequest('larry@example.test', Uuid::v4());

        $client->jsonRequest('POST', '/api/user/invite', (array) $request);

        $response = $client->getResponse();

        self::assertEquals(404, $response->getStatusCode());
    }

    public function testFailsUnauthenticated(): void
    {
        $client = self::createClient();

        $request = new InviteUserRequest('larry@example.test', Uuid::v4()->toString());

        $client->jsonRequest('POST', '/api/user/invite', (array) $request);

        self::assertEquals(401, $client->getResponse()->getStatusCode());
    }
}
