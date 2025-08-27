<?php

namespace Veliu\RateManu\Domain;

use Symfony\Component\Uid\Uuid;

interface EntityInterface
{
    public function getId(): Uuid;

    /** @phpstan-return non-empty-string */
    public static function getName(): string;
}
