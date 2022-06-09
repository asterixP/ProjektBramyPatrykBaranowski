<?php

namespace App\Order\Action;

use App\Order\Application\UseCase\GetOrders\GetOrdersHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GetOrdersAction
{
    private GetOrdersHandler $handler;

    /**
     * @param GetOrdersHandler $handler
     */
    public function __construct(GetOrdersHandler $handler)
    {
        $this->handler = $handler;
    }

    public function __invoke(): JsonResponse
    {
        $result = $this->handler->handle();
        return new JsonResponse($result->getData(), $result->getStatus());
    }
}