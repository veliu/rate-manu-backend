<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Symfony\Validator\Constraint;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class EntityExistsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof EntityExists) {
            throw new UnexpectedTypeException($constraint, EntityExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedValueException($value, 'string');
        }

        $value = (string) $value;

        $repository = $this->entityManager->getRepository($constraint->entityClass);
        $entity = $repository->findOneBy([$constraint->field => $value]);

        if (null === $entity) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ entityClass }}', $constraint->entityClass::getName())
                ->setParameter('{{ field }}', $constraint->field)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
