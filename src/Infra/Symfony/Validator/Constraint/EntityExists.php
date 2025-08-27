<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Symfony\Validator\Constraint;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;
use Veliu\RateManu\Domain\EntityInterface;

#[\Attribute]
final class EntityExists extends Constraint
{
    /**
     * @phpstan-param class-string<EntityInterface> $entityClass
     * @phpstan-param non-empty-string $field
     * @phpstan-param non-empty-string $message
     */
    #[HasNamedArguments]
    public function __construct(
        public string $entityClass,
        public string $field = 'id',
        public string $message = 'The {{ entityClass }} with {{ field }} "{{ value }}" does not exist.',
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct([
            'entityClass' => $entityClass,
        ], $groups, $payload);
    }

    public function getRequiredOptions(): array
    {
        return ['entityClass'];
    }
}
