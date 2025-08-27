<?php

declare(strict_types=1);

namespace Veliu\RateManu\Domain\User;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Veliu\RateManu\Domain\Group\Group;

#[ORM\Table(name: 'user_group_relation')]
#[ORM\Entity]
#[ORM\UniqueConstraint(name: 'user_group', columns: ['user_id', 'group_id'])]
class GroupRelation
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    public readonly Uuid $id;

    public function __construct(
        #[ManyToOne(targetEntity: User::class, inversedBy: 'userGroups')]
        public readonly User $user,
        #[ManyToOne(targetEntity: Group::class, inversedBy: 'userGroups')]
        public readonly Group $group,
        #[ORM\Column(type: 'string', enumType: Role::class)]
        public readonly Role $role,
    ) {
        $this->id = Uuid::v4();
        $now = new \DateTime('now');
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }
}
