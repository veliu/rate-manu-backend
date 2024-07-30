<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;
use Veliu\RateManu\Infra\Doctrine\Repository\UserRepository;

#[ORM\Table(name: 'app_user')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
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
}
