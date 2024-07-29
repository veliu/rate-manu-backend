<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Exception;

class MailNotSend extends \RuntimeException
{
    /** @psalm-param non-empty-string $message */
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct($message, 400, $previous);
    }
}
