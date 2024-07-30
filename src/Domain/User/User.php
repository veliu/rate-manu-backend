<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
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

    /** @var Collection<string, Group> */
    #[ManyToMany(targetEntity: Group::class, inversedBy: 'users')]
    #[JoinTable(name: 'users_groups')]
    private Collection $groups;

    #[ORM\Column(type: 'string', enumType: Status::class)]
    private Status $status;

    /**
     * @psalm-param non-empty-string|null $password
     * @psalm-param list<non-empty-string> $roles
     */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        readonly public Uuid $id,

        #[ORM\Column(type: EmailAddress::DATABASE_TYPE_NAME, unique: true)]
        readonly public EmailAddress $email,

        #[ORM\Column(type: 'string')]
        public ?string $password,

        #[ORM\Column(type: 'json')]
        public array $roles = [],
    ) {
        $this->status = Status::PENDING_REGISTRATION;
        $now = new \DateTime('now');
        $this->createdAt = $now;
        $this->updatedAt = $now;
        $this->groups = new ArrayCollection();
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

    public function eraseCredentials(): void
    {
        $this->password = null;
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
        $group->addMember($this);
        $this->groups->set($group->id->toString(), $group);
    }

    public function removeFromGroup(Group $group): void
    {
        $group->removeMember($this);
        $this->groups->remove($group->id->toString());
    }
}
