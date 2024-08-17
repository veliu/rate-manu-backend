<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Symfony\Filesystem;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Veliu\RateManu\Domain\File\FileStorageInterface;

final readonly class FileStorage implements FileStorageInterface
{
    private const string FOOD_IMAGE_PATH = '/uploads/food/';

    public function __construct(
        private SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public')] private string $projectDirectory,
    ) {
    }

    public function createFile(UploadedFile $file): string
    {
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFileName);
        $newFilename = sprintf(
            '%s-%s.%s',
            $safeFilename,
            uniqid(),
            $file->guessExtension()
        );

        $file->move($this->projectDirectory.self::FOOD_IMAGE_PATH, $newFilename);

        return self::FOOD_IMAGE_PATH.$newFilename;
    }

    public function deleteFile(string $file): void
    {
        $filesystem = new Filesystem();

        $absolutePath = $this->projectDirectory.$file;

        if ($filesystem->exists($absolutePath)) {
            $filesystem->remove($absolutePath);
        }
    }
}
