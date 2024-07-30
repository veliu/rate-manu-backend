<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Exception;

final class NotCreatedException extends \RuntimeException
{
    /** @psalm-param non-empty-string $message */
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, 400, $previous);
    }
}
