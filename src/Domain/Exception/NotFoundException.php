<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Exception;

class NotFoundException extends \RuntimeException
{
    /** @psalm-param non-empty-string $message */
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}
