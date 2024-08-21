<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Group;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\User\GroupRelation;
use Veliu\RateManu\Domain\User\Role;
use Veliu\RateManu\Domain\User\User;
use Veliu\RateManu\Infra\Doctrine\Repository\GroupRepository;

#[ORM\Table(name: 'app_group')]
#[ORM\Entity(repositoryClass: GroupRepository::class)]
class Group
{
    use TimestampableEntity;

    /** @var Collection<string, User> */
    #[ManyToMany(targetEntity: User::class, inversedBy: 'groups')]
    private Collection $users;

    /** @var Collection<int, GroupRelation> */
    #[OneToMany(targetEntity: GroupRelation::class, mappedBy: 'group', cascade: ['persist', 'remove'])]
    private Collection $userGroups;

    /**
     * @phpstan-param non-empty-string $name
     */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        readonly public Uuid $id,

        #[ORM\Column]
        public string $name,
    ) {
        $this->users = new ArrayCollection();
        $now = new \DateTime('now');
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    /** @phpstan-return Collection<string, User> */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addMember(User $user): void
    {
        $user->addToGroup($this);
        $this->users->set($user->id->toString(), $user);
    }

    public function removeMember(User $user): void
    {
        $user->removeFromGroup($this);
        $this->users->remove($user->id->toString());
    }

    public function createUserRelation(User $user, Role $role): void
    {
        $this->userGroups->add(new GroupRelation($user, $this, $role));
    }

    public function removeUserRelation(User $user): void
    {
        foreach ($this->userGroups as $userRelation) {
            if ($userRelation->user->id->equals($user->id)) {
                $this->userGroups->remove($userRelation);

                return;
            }
        }
    }

    /** @return Collection<int, GroupRelation> */
    public function getGroupRelations(): Collection
    {
        return $this->userGroups;
    }
}
