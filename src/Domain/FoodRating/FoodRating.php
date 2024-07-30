<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\FoodRating;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Infra\Doctrine\Repository\FoodRatingRepository;

#[ORM\Entity(repositoryClass: FoodRatingRepository::class)]
class FoodRating
{
    use TimestampableEntity;

    /**
     * @phpstan-param non-empty-string $name
     * @phpstan-param int<1, 6> $rating
     */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        readonly public Uuid $id,

        #[ORM\Column]
        public string $name,

        #[ORM\Column]
        public string $description,

        #[ORM\Column]
        private int $rating,
    ) {
        $now = new \DateTime('now');
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    public function setRating(int $rating): void
    {
        if ($rating < 1 || $rating > 6) {
            throw new \InvalidArgumentException('Rating must be between 1 and 6');
        }

        $this->rating = $rating;
    }

    public function getRating(): int
    {
        return $this->rating;
    }
}
