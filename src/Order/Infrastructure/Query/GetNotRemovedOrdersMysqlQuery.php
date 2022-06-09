<?php

namespace App\Order\Infrastructure\Query;

use App\Order\Application\DTO\AddressDTO;
use App\Order\Application\DTO\ItemDTO;
use App\Order\Application\DTO\OrderDTO;
use App\Order\Application\Query\GetNotRemovedOrdersQuery;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class GetNotRemovedOrdersMysqlQuery implements GetNotRemovedOrdersQuery
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return OrderDTO[]
     * @throws Exception
     */
    public function execute(): array
    {
        $orderQuery = $this->connection->createQueryBuilder()
            ->select('id, user_id, total_price, billing_address, creation_date, accepted')
            ->from('orders')
            ->where('removed = false');
        $orderQuery->executeQuery();
        $rawOrder = $orderQuery->fetchAllAssociative();

        $orderCollection = [];
        foreach ($rawOrder as $order) {
            $orderCollection[] = new OrderDTO(
                (int)$order['id'],
                (int)$order['user_id'],
                $this->getItemsForOrder($order['id']),
                is_null($order['total_price']) ? null : (int) $order['total_price'],
                $this->createBillingAddress(json_decode($order['billing_address'], true)),
                new DateTimeImmutable($order['creation_date']),
                $order['accepted']
            );
        }

        return $orderCollection;
    }


    /**
     * @param int $orderId
     * @return array<int,ItemDTO>
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
            $items[] = new ItemDTO($item['description']);
        }

        return $items;
    }

    /**
     * @param array<string,string> $billingAddress
     * @return AddressDTO
     */
    private function createBillingAddress(array $billingAddress): AddressDTO
    {
        return new AddressDTO(
            $billingAddress['first_name'],
            $billingAddress['last_name'],
            $billingAddress['street_name'],
            $billingAddress['city_name'],
            $billingAddress['postal_code'],
        );
    }
}