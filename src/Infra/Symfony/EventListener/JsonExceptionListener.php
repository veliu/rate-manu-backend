<?php

declare(strict_types=1);

namespace Veliu\RateManu\Infra\Symfony\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::EXCEPTION, method: 'onKernelException')]
final readonly class JsonExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
    }
}
