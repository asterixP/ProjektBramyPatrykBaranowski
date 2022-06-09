<?php

namespace App\Order\Action;

use App\Order\Application\UseCase\EditOrder\EditOrderHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EditOrderAction
{
    private EditOrderHandler $handler;

    /**
     * @param EditOrderHandler $handler
     */
    public function __construct(EditOrderHandler $handler)
    {
        $this->handler = $handler;
    }

    public function __invoke(int $orderId, Request $request): JsonResponse
    {
        $result = $this->handler->handle($orderId, json_decode((string)$request->getContent(), true));
        return new JsonResponse($result->getData(), $result->getStatus());
    }
}