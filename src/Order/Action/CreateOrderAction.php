<?php

namespace App\Order\Action;

use App\Order\Application\UseCase\CreateOrder\CreateOrderHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CreateOrderAction
{
    private CreateOrderHandler $handler;

    /**
     * @param CreateOrderHandler $handler
     */
    public function __construct(CreateOrderHandler $handler)
    {
        $this->handler = $handler;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $result = $this->handler->handle(json_decode((string) $request->getContent(), true));
        return new JsonResponse($result->getData(), $result->getStatus());
    }
}