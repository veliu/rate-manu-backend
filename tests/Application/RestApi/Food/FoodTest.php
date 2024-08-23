<?php

declare(strict_types=1);

namespace Veliu\RateManu\Tests\Application\RestApi\Food;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Tests\Application\RestApi\ApplicationTestCase;

use function PHPUnit\Framework\assertEquals;
use function Psl\Json\decode;
use function Psl\Type\int;
use function Psl\Type\non_empty_string;
use function Psl\Type\nullable;
use function Psl\Type\positive_int;
use function Psl\Type\shape;
use function Psl\Type\vec;

final class FoodTest extends ApplicationTestCase
{
    public function testCreateAndDelete(): void
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
        self::assertIsString($response->getContent());
        self::assertJson($response->getContent());
        $data = decode($response->getContent());

        $foodResponseType = shape([
            'id' => non_empty_string(),
            'name' => non_empty_string(),
            'description' => nullable(non_empty_string()),
            'author' => non_empty_string(),
            'group' => non_empty_string(),
            'createdAt' => non_empty_string(),
            'updatedAt' => non_empty_string(),
            'image' => nullable(non_empty_string()),
            'averageRating' => int(),
            'ratings' => vec(shape([
                'id' => non_empty_string(),
                'food' => non_empty_string(),
                'rating' => positive_int(),
                'createdBy' => non_empty_string(),
                'createdAt' => non_empty_string(),
                'updatedAt' => non_empty_string(),
            ])),
        ]);

        self::assertTrue($foodResponseType->matches($data));

        self::assertEquals('TK Pizza', $data['name']);
        self::assertNull($data['description']);
        self::assertEquals($foodId->toString(), $data['id']);
        self::assertTrue(Uuid::isValid($data['author']));
        self::assertTrue(Uuid::isValid($data['group']));
        self::assertEquals(0, $data['averageRating']);

        $projectDir = non_empty_string()->coerce($this->getContainer()->getParameter('kernel.project_dir'));

        $testFile = $projectDir.'/tests/assets/test.jpg';
        $tempTestFile = $projectDir.'/tests/assets/temp_test.jpg';

        copy($testFile, $tempTestFile);

        $file = new UploadedFile(
            $tempTestFile,
            'test.jpg',
            'image/jpeg',
        );
        $client->request(
            method: 'POST',
            uri: '/api/food/'.$data['id'].'/update-image',
            files: [
                'image' => $file,
            ],
            server: [
                'header' => [
                    'Content-Type' => 'multipart/form-data',
                ],
            ]
        );

        $response = $client->getResponse();
        assertEquals(200, $response->getStatusCode());
        self::assertIsString($response->getContent());
        self::assertJson($response->getContent());
        $data = decode($response->getContent());

        self::assertTrue(shape([
            'image' => non_empty_string(),
        ], true)->matches($data));

        $image = $data['image'];
        $imageUrl = parse_url($image);

        $localFile = $projectDir.'/public'.$imageUrl['path'];
        self::assertFileExists($localFile);

        $client->jsonRequest(
            method: 'DELETE',
            uri: '/api/food/'.$foodId->toString(),
        );

        $response = $client->getResponse();
        assertEquals(204, $response->getStatusCode());
        self::assertFileDoesNotExist($localFile);
    }
}
