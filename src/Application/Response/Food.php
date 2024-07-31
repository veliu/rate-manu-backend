<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\Response;

use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Food\Food as Entity;

use function Psl\Type\non_empty_string;

final readonly class Food
{
    public function __construct(
        public Uuid $id,
        public string $name,
        public ?string $description,
        public Uuid $author,
        public Uuid $group,
        public string $createdAt,
        public string $updatedAt,
    ) {
    }

    public static function fromEntity(Entity $entity): self
    {
        return new self(
            $entity->id,
            $entity->name,
            $entity->description,
            $entity->author->id,
            $entity->group->id,
            non_empty_string()->coerce($entity->getCreatedAt()?->format(\DateTime::ATOM)),
            non_empty_string()->coerce($entity->getUpdatedAt()?->format(\DateTime::ATOM)),
        );
    }
}
