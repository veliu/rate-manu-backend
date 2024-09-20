<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain;

enum FilterOperator: string
{
    case EQUALS = '=';
    case NOT_EQUALS = '<>';
    case GREATER = '>';
    case GREATER_EQUALS = '>=';
    case LESS = '<';
    case LESS_EQUALS = '<=';
}
