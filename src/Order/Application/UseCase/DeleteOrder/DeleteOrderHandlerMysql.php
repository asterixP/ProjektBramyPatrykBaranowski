<?php

namespace App\Order\Application\UseCase\DeleteOrder;

use App\Common\Application\Payload\ApiPayload;
use App\Order\Domain\Repository\OrderRepository;
use Exception;
use Throwable;

class DeleteOrderHandlerMysql implements DeleteOrderHandler
{
    private OrderRepository $repository;

    /**
     * @param OrderRepository $repository
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(int $orderId): ApiPayload
    {
        try {
            $order = $this->repository->get($orderId);
            if($order->isRemoved()){
                throw new Exception();
            }
            $this->repository->deleteOrder($order);

            return new ApiPayload(['message' => 'Usunięto zamówienie'], 200);
        } catch (Throwable) {
            return new ApiPayload(['message' => 'Nie można usunąć zamówienia'], 500);
        }
    }
}