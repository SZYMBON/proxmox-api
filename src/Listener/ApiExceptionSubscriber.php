<?php

namespace App\Listener;

use App\Exception\ApiException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $request   = $event->getRequest();


        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
            $headers= $exception->getHeaders();
        }

        if ('application/json' === $request->headers->get('Content-Type'))
        {
            $response = new JsonResponse([
                'message'       => $exception->getMessage(),
                'code'          => $statusCode,
                'traces'        => $exception->getTrace()
            ]);

            $response->setStatusCode($statusCode);

            if(!empty($headers)){
                $response->headers->replace($exception->getHeaders());
            }

            $event->setResponse($response);
        }
    }
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => 'onKernelException'
        );
    }
}