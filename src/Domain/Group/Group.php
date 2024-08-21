<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Group;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\User\GroupRelation;
use Veliu\RateManu\Infra\Doctrine\Repository\GroupRepository;

#[ORM\Table(name: 'app_group')]
#[ORM\Entity(repositoryClass: GroupRepository::class)]
class Group
{
    use TimestampableEntity;

    /** @var Collection<int, GroupRelation> */
    #[OneToMany(targetEntity: GroupRelation::class, mappedBy: 'group', cascade: ['persist', 'remove'])]
    private Collection $userGroups;

    /**
     * @phpstan-param non-empty-string $name
     */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        private Uuid $id,

        #[ORM\Column]
        public string $name,
    ) {
        $now = new \DateTime('now');
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    /** @phpstan-return Collection<int, GroupRelation> */
    public function getUserRelations(): Collection
    {
        return $this->userGroups;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }
}
