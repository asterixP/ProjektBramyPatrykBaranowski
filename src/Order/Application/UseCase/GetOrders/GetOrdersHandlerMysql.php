<?php

namespace App\Order\Application\UseCase\GetOrders;

use App\Common\Application\Payload\ApiPayload;
use App\Order\Application\DTO\OrderDTO;
use App\Order\Application\Query\GetNotRemovedOrdersQuery;

use Throwable;

final class GetOrdersHandlerMysql implements GetOrdersHandler
{
    private GetNotRemovedOrdersQuery $getNotRemovedOrdersQuery;

    /**
     * @param GetNotRemovedOrdersQuery $getNotRemovedOrdersQuery
     */
    public function __construct(GetNotRemovedOrdersQuery $getNotRemovedOrdersQuery)
    {
        $this->getNotRemovedOrdersQuery = $getNotRemovedOrdersQuery;
    }

    public function handle(): ApiPayload
    {
        try {
            $orders = $this->getNotRemovedOrdersQuery->execute();

            return new ApiPayload(
                [
                    'message' => 'Ok',
                    'orders' => array_map(
                        function(OrderDTO $order){
                            return $order->toArray();
                        },
                        $orders
                    )
                ],
                201
            );
        } catch (Throwable) {
            return new ApiPayload(['message' => 'Nie udało się pobrać zamówień'], 500);
        }
    }
}