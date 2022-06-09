<?php

namespace App\Order\Infrastructure\Repository;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Repository\OrderRepository;
use App\Order\Domain\ValueObject\Address;
use App\Order\Domain\ValueObject\Item;
use App\Order\Domain\ValueObject\Price;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class OrderMysqlRepository implements OrderRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws Exception
     */
    public function nextId(): int
    {
        $query = $this->connection->createQueryBuilder()
            ->select('max(id) as max_id')
            ->from('orders', 'o');
        $query->executeQuery();
        $rawResult = $query->fetchAllAssociative();

        if (empty($rawResult)) {
            return 1;
        }

        if (is_null($rawResult[0]['max_id'])) {
            return 1;
        }

        return $rawResult[0]['max_id'] + 1;
    }

    /**
     * @throws Exception
     */
    public function insertOrder(Order $order): void
    {
        $this->connection->createQueryBuilder()
            ->insert('orders')
            ->values(array(
                'id' => '?',
                'user_id' => '?',
                'total_price' => '?',
                'billing_address' => '?',
                'creation_date' => '?',
                'accepted' => '?',
                'removed' => '?',
            ))
            ->setParameter(0, $order->getId())
            ->setparameter(1, $order->getUserId())
            ->setparameter(2, is_null($order->getTotalPrice()) ? null : $order->getTotalPrice()->getValue())
            ->setparameter(3, json_encode($order->getBillingAddress()->toArray()))
            ->setparameter(4, $order->getCreationDate()->format('Y-m-d H:i:s'))
            ->setparameter(5, $order->isAccepted())
            ->setparameter(6, false)
            ->executeQuery();

        foreach ($order->getOrderItems() as $item) {
            $this->connection->createQueryBuilder()
                ->insert('orders_items')
                ->values(array(
                    'order_id' => '?',
                    'description' => '?'
                ))
                ->setparameter(0, $order->getId())
                ->setparameter(1, $item->getDescription())
                ->executeQuery();
        }
    }

    /**
     * @throws Exception
     */
    public function get(int $orderId): Order
    {
        $orderQuery = $this->connection->createQueryBuilder()
            ->select('id, user_id, total_price, billing_address, creation_date, accepted, removed')
            ->from('orders')
            ->where('id = :order_id')
            ->setparameter('order_id', $orderId);
        $orderQuery->executeQuery();
        $rawOrder = $orderQuery->fetchAllAssociative()[0];

        return new Order(
            (int)$rawOrder['id'],
            (int)$rawOrder['user_id'],
            $this->getItemsForOrder($orderId),
            $this->createBillingAddress(json_decode($rawOrder['billing_address'], true)),
            is_null($rawOrder['total_price']) ? null : new Price($rawOrder['total_price']),
            new DateTimeImmutable($rawOrder['creation_date']),
            $rawOrder['accepted'],
            $rawOrder['removed'],
        );
    }

    public function updateOrder(Order $order): void
    {
        $this->connection->createQueryBuilder()
            ->update('orders')
            ->set('total_price', ':total_price')
            ->set('accepted', ':accepted')
            ->set('billing_address', ':billing_address')
            ->where('id = :id')
            ->setParameter('total_price', is_null($order->getTotalPrice()) ? null : $order->getTotalPrice()->getValue())
            ->setParameter('accepted', is_null($order->isAccepted()) ? null : $order->isAccepted())
            ->setParameter('billing_address', json_encode($order->getBillingAddress()->toArray()))
            ->setParameter('id', $order->getId())
            ->executeQuery();

        $this->connection->createQueryBuilder()
            ->delete('orders_items')
            ->where('id = :id')
            ->setParameter('id', $order->getId())
            ->executeQuery();

        foreach ($order->getOrderItems() as $item) {
            $this->connection->createQueryBuilder()
                ->insert('orders_items')
                ->values(array(
                    'order_id' => '?',
                    'description' => '?'
                ))
                ->setparameter(0, $order->getId())
                ->setparameter(1, $item->getDescription())
                ->executeQuery();
        }
    }

    public function deleteOrder(Order $order): void
    {
        $this->connection->createQueryBuilder()
            ->update('orders')
            ->set('removed', true)
            ->where('id = :id')
            ->setParameter('id', $order->getId())
            ->executeQuery();
    }

    /**
     * @throws Exception
     */
    private function getItemsForOrder(int $orderId): array
    {
        $orderItemsQuery = $this->connection->createQueryBuilder()
            ->select('id, order_id, description')
            ->from('orders_items')
            ->where('order_id = :order_id')
            ->setparameter('order_id', $orderId);
        $orderItemsQuery->executeQuery();
        $rawOrderItems = $orderItemsQuery->fetchAllAssociative();

        $items = [];
        foreach ($rawOrderItems as $item) {
            $items[] = new Item($item['description']);
        }

        return $items;
    }

    /**
     * @param array<string,string> $billingAddress
     * @return Address
     */
    private function createBillingAddress(array $billingAddress): Address
    {
        return new Address(
            $billingAddress['first_name'],
            $billingAddress['last_name'],
            $billingAddress['street_name'],
            $billingAddress['city_name'],
            $billingAddress['postal_code'],
        );
    }
}