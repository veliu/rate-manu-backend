<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\UserRepositoryInterface;
use Veliu\RateManu\Domain\ValueObject\Email;

#[ORM\Entity(repositoryClass: UserRepositoryInterface::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @psalm-param non-empty-string|null $password
     * @psalm-param list<non-empty-string> $roles
     */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        readonly public Uuid $id,

        #[ORM\Column(type: Email::DATABASE_TYPE_NAME)]
        readonly public Email $email,

        #[ORM\Column(type: 'string')]
        public ?string $password,

        #[ORM\Column(type: 'json')]
        public array $roles = [],
    ) {
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
