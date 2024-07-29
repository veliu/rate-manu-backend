<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain;

interface DataBaseType
{
    public static function getDatabaseTypeName(): string;
}
