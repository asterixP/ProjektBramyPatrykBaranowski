<?php

namespace App\Order\Application\UseCase\EditOrder;

use App\Common\Application\Payload\ApiPayload;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Repository\OrderRepository;
use App\Order\Domain\ValueObject\Address;
use App\Order\Domain\ValueObject\Price;
use Throwable;

final class EditOrderHandlerMysql implements EditOrderHandler
{
    private OrderRepository $repository;

    /**
     * @param OrderRepository $repository
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(int $orderId, array $data): ApiPayload
    {
        try {
            $order = $this->repository->get($orderId);
            $this->changeOrderParameters($order, $data);
            $this->repository->updateOrder($order);

            return new ApiPayload(['message' => 'Edytowano zamówienie'], 200);
        } catch (Throwable) {
            return new ApiPayload(['message' => 'Nie można edytować zamówienia'], 500);
        }
    }

    private function changeOrderParameters(Order $order, array $data)
    {
        if (key_exists('order_items', $data)) {
            $order->changeOrderItemsTo($data['order_items']);
        }
        if (key_exists('total_price', $data)) {
            $order->changeTotalPriceTo(new Price((int)$data['total_price']));
        }
        if (key_exists('accepted', $data)) {
            if ((bool)$data['accepted'] === true) {
                $order->accept();
            }
            if ((bool)$data['accepted'] === false) {
                $order->discard();
            }
        }
        if (key_exists('billing_address', $data)) {
            $order->changeBillingAddressTo(
                new Address(
                    $data['billing_address']['first_name'],
                    $data['billing_address']['last_name'],
                    $data['billing_address']['street_name'],
                    $data['billing_address']['city_name'],
                    $data['billing_address']['postal_code'],
                )
            );
        }
    }
}