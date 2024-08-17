<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFoundException extends NotFoundHttpException
{
    /** @psalm-param non-empty-string $message */
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, $previous, 404);
    }
}
