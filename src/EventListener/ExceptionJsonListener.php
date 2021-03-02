<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionJsonListener
{
    // TODO add fos_rest or map of exception => httpcode
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $customResponse = new JsonResponse([
            'error' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ], $exception->getCode() > 0 ? $exception->getCode() : 400);
        $event->setResponse($customResponse);
    }
}
