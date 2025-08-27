<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\Ingredient;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\EntityInterface;
use Veliu\RateManu\Domain\Group\Group;
use Veliu\RateManu\Domain\User\User;

#[ORM\Entity]
class Ingredient implements EntityInterface
{
    use TimestampableEntity;

    /**
     * @phpstan-param non-empty-string $name
     */
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        public Uuid $id,

        #[ORM\Column]
        public readonly string $name,

        #[ORM\Column(enumType: UnitEnum::class)]
        public readonly UnitEnum $defaultUnit,

        #[ORM\ManyToOne(targetEntity: User::class)]
        #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
        public readonly User $author,

        #[ORM\ManyToOne(targetEntity: Group::class)]
        #[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id', nullable: true)]
        public readonly ?Group $group = null,
    ) {
        $now = new \DateTime('now');
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public static function getName(): string
    {
        return 'Ingredient';
    }
}
