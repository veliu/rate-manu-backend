<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Rating;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Food\Food;
use Veliu\RateManu\Domain\User\User;
use Veliu\RateManu\Infra\Doctrine\Repository\RatingRepository;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
class Rating
{
    use TimestampableEntity;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        readonly public Uuid $id,

        #[ORM\ManyToOne(targetEntity: User::class)]
        #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
        public readonly User $author,

        #[ORM\ManyToOne(targetEntity: Food::class)]
        #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
        public readonly Food $food,

        #[ORM\Column(type: 'integer')]
        private int $rating,
    ) {
        $now = new \DateTime('now');
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function updateRating(int $rating): void
    {
        $this->rating = $rating;
    }
}
