<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Food;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Comment\Comment;
use Veliu\RateManu\Domain\Group\Group;
use Veliu\RateManu\Domain\Rating\Rating;
use Veliu\RateManu\Domain\User\User;
use Veliu\RateManu\Infra\Doctrine\Repository\FoodRepository;

#[ORM\Entity(repositoryClass: FoodRepository::class)]
class Food
{
    use TimestampableEntity;

    /**
     * @phpstan-param non-empty-string $name
     * @phpstan-param non-empty-string|null $description
     *
     * @param Collection<int, Rating>  $ratings
     * @param Collection<int, Comment> $comments
     */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        readonly public Uuid $id,

        #[ORM\Column]
        public string $name,

        #[ORM\Column(nullable: true)]
        public ?string $description,

        #[ORM\ManyToOne(targetEntity: Group::class)]
        #[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id')]
        public readonly Group $group,

        #[ORM\ManyToOne(targetEntity: User::class)]
        #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
        public readonly User $author,

        #[ORM\OneToMany(targetEntity: Rating::class, mappedBy: 'food', cascade: ['remove'])]
        public Collection $ratings = new ArrayCollection(),

        #[ORM\Column(nullable: true)]
        private ?string $image = null,

        #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'food', cascade: ['remove'])]
        public Collection $comments = new ArrayCollection(),
    ) {
        $now = new \DateTime('now');
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    /** @phpstan-return int<0,6> */
    public function getAverageRating(): int
    {
        $ratings = [];

        foreach ($this->ratings as $rating) {
            $ratings[] = $rating->getRating();
        }

        $count = count($ratings);

        if (0 === $count) {
            return 0;
        }

        $avgRating = (int) round(array_sum($ratings) / count($ratings));

        if ($avgRating > 6 || $avgRating < 0) {
            throw new \LogicException('Average rating is more than 6');
        }

        return $avgRating;
    }

    public function getRatingForUser(User $user): ?Rating
    {
        foreach ($this->ratings as $rating) {
            if ($rating->createdBy->id->equals($user->id)) {
                return $rating;
            }
        }

        return null;
    }
}
