<?php

namespace App\Order\Application\UseCase\CreateOrder;

use App\Common\Application\Payload\ApiPayload;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Exception\InvalidInput;
use App\Order\Domain\Repository\OrderRepository;
use App\Order\Domain\ValueObject\Address;
use Throwable;

final class CreateOrderHandlerMysql implements CreateOrderHandler
{
    private OrderRepository $repository;

    /**
     * @param OrderRepository $repository
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(array $data): ApiPayload
    {
        try {
            $this->validate($data);
            $userId = $data['user_id'];
            $orderItemsRaw = $data['order_items'];
            $billingAddress = $data['billing_address'];

            $order = new Order(
                $this->repository->nextId(),
                $userId,
                $orderItemsRaw,
                new Address(
                    $billingAddress['first_name'],
                    $billingAddress['last_name'],
                    $billingAddress['street_name'],
                    $billingAddress['city_name'],
                    $billingAddress['postal_code'],
                )
            );

            $this->repository->insertOrder($order);

            return new ApiPayload(['message' => 'Dodano zamównienie'], 201);
        } catch (InvalidInput) {
            return new ApiPayload(['message' => 'Brak wymaganych pól'], 400);
        } catch (Throwable) {
            return new ApiPayload(['message' => 'Nie udało się dodać zamówienia'], 500);
        }
    }

    private function validate(array $data): void
    {
        if (key_exists('user_id', $data) === false && false === is_int($data['user_id'])) {
            throw new InvalidInput();
        }
        if (key_exists('order_items', $data) === false) {
            throw new InvalidInput();
        }
        if (key_exists('billing_address', $data) === false) {
            throw new InvalidInput();
        }
        if (key_exists('first_name', $data['billing_address']) === false) {
            throw new InvalidInput();
        }
        if (key_exists('last_name', $data['billing_address']) === false) {
            throw new InvalidInput();
        }
        if (key_exists('street_name', $data['billing_address']) === false) {
            throw new InvalidInput();
        }
        if (key_exists('city_name', $data['billing_address']) === false) {
            throw new InvalidInput();
        }
        if (key_exists('postal_code', $data['billing_address']) === false) {
            throw new InvalidInput();
        }
    }
}