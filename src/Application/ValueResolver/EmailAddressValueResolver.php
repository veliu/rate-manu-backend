<?php

declare(strict_types=1);

namespace Veliu\RateManu\Application\ValueResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Veliu\RateManu\Domain\ValueObject\EmailAddress;

#[AsTargetedValueResolver(self::class)]
final class EmailAddressValueResolver implements ValueResolverInterface
{
    /** @phpstan-return iterable<EmailAddress> */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $value = $request->attributes->get($argument->getName());

        return [EmailAddress::fromAny($value)];
    }
}
