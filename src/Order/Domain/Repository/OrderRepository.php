<?php

namespace App\Order\Domain\Repository;

use App\Order\Domain\Entity\Order;
use Doctrine\DBAL\Exception;

interface OrderRepository
{
    /**
     * @throws Exception
     */
    public function nextId(): int;

    /**
     * @throws Exception
     */
    public function insertOrder(Order $order): void;

    /**
     * @throws Exception
     */
    public function get(int $orderId): Order;

    /**
     * @throws Exception
     */
    public function updateOrder(Order $order): void;

    /**
     * @throws Exception
     */
    public function deleteOrder(Order $order): void;
}