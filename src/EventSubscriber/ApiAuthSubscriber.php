<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiAuthSubscriber implements EventSubscriberInterface
{
    private const string REQUIRED_HEADER = 'X-API-User-Name';

    public function __construct(
        private readonly string $apiKey
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 10],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!str_starts_with($request->getPathInfo(), '/api/')) {
            return;
        }

        $apiKey = $request->headers->get(self::REQUIRED_HEADER);

        if ($apiKey !== $this->apiKey) {
            $response = new JsonResponse([
                '@context' => '/v1/contexts/Error',
                '@type' => 'hydra:Error',
                'hydra:title' => 'An error occurred',
                'hydra:description' => 'Access denied. Invalid or missing API key.',
                'status' => 403,
                'detail' => 'Access denied'
            ], Response::HTTP_FORBIDDEN);

            $event->setResponse($response);
        }
    }
}
