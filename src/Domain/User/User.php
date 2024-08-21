<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Group\Group;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;
use Veliu\RateManu\Infra\Doctrine\Repository\UserRepository;

#[ORM\Table(name: 'app_user')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;

    /** @var Collection&ArrayCollection<string, Group> */
    #[ManyToMany(targetEntity: Group::class, inversedBy: 'users', cascade: ['persist'])]
    #[JoinTable(name: 'users_groups')]
    private Collection $groups;

    /** @var Collection<int, GroupRelation> */
    #[OneToMany(targetEntity: GroupRelation::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private Collection $userGroups;

    #[ORM\Column(type: 'string', enumType: Status::class)]
    private Status $status;

    /**
     * @psalm-param non-empty-string|null $password
     */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        readonly public Uuid $id,

        #[ORM\Column(type: EmailAddress::DATABASE_TYPE_NAME, unique: true)]
        readonly public EmailAddress $email,

        /** @phpstan-var list<non-empty-string> */
        #[ORM\Column(type: 'json')]
        private array $roles,

        #[ORM\Column(type: 'string', nullable: true)]
        public ?string $password = null,
    ) {
        $this->status = Status::PENDING_REGISTRATION;
        $now = new \DateTime('now');
        $this->createdAt = $now;
        $this->updatedAt = $now;
        $this->groups = new ArrayCollection();
        $this->userGroups = new ArrayCollection();
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function activate(): void
    {
        $this->status = Status::ACTIVE;
    }

    public function deactivate(): void
    {
        $this->status = Status::INACTIVE;
    }

    /** @psalm-param non-empty-string $password */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /** @psalm-return non-empty-string|null */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @psalm-return list<non-empty-string>
     *
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @psalm-return list<Role>
     *
     * @return Role[]
     */
    public function getRoleList(): array
    {
        return array_map(fn (string $roleString) => Role::from($roleString), $this->roles);
    }

    public function eraseCredentials(): void
    {
    }

    /** @psalm-return non-empty-string */
    public function getUserIdentifier(): string
    {
        return $this->email->value;
    }

    /** @phpstan-return Collection<string, Group> */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addToGroup(Group $group): void
    {
        $this->groups->set($group->id->toString(), $group);
    }

    public function removeFromGroup(Group $group): void
    {
        $this->groups->remove($group->id->toString());
    }

    public function createGroupRelation(Group $group, Role $role): void
    {
        $this->userGroups->add(new GroupRelation($this, $group, $role));
    }

    public function removeGroupRelation(Group $group): void
    {
        foreach ($this->userGroups as $groupRelation) {
            if ($groupRelation->group->id->equals($group->id)) {
                $this->userGroups->remove($groupRelation);

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
