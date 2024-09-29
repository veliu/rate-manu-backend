<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Comment;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Food\Food;
use Veliu\RateManu\Domain\User\User;

#[ORM\Entity]
class Comment
{
    use TimestampableEntity;

    /** @phpstan-param non-empty-string $comment */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        public readonly Uuid $id,

        #[ORM\ManyToOne(targetEntity: User::class)]
        #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
        public readonly User $author,

        #[ORM\ManyToOne(targetEntity: Food::class)]
        #[ORM\JoinColumn(name: 'food_id', referencedColumnName: 'id')]
        public readonly Food $food,

        #[ORM\Column(type: 'text', nullable: false)]
        private string $comment,
    ) {
        $now = new \DateTime('now');
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    /** @phpstan-return non-empty-string */
    public function getComment(): string
    {
        return $this->comment;
    }

    /** @phpstan-param non-empty-string $comment */
    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }
}
