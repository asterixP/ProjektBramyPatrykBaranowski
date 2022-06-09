<?php

namespace App\Order\Action;

use App\Order\Application\UseCase\DeleteOrder\DeleteOrderHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DeleteOrderAction
{
    private DeleteOrderHandler $handler;

    /**
     * @param DeleteOrderHandler $handler
     */
    public function __construct(DeleteOrderHandler $handler)
    {
        $this->handler = $handler;
    }

    public function __invoke(int $orderId, Request $request): JsonResponse
    {
        $result = $this->handler->handle($orderId);
        return new JsonResponse($result->getData(), $result->getStatus());
    }
}