<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileStorageInterface
{
    public function createFile(UploadedFile $file): string;

    public function deleteFile(string $file): void;
}
