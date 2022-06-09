<?php

namespace App\Order\Application\DTO;

use DateTimeImmutable;

class OrderDTO
{
    private int $id;

    private int $userId;

    private array $orderItems;

    private ?int $totalPrice;

    private AddressDTO $billingAddress;

    private DateTimeImmutable $creationDate;

    private ?bool $accepted;

    /**
     * @param int $id
     * @param int $userId
     * @param array<int,ItemDTO> $orderItems
     * @param int|null $totalPrice
     * @param AddressDTO $billingAddress
     * @param DateTimeImmutable $creationDate
     * @param bool|null $accepted
     */
    public function __construct(int $id, int $userId, array $orderItems, ?int $totalPrice, AddressDTO $billingAddress, DateTimeImmutable $creationDate, ?bool $accepted)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->orderItems = $orderItems;
        $this->totalPrice = $totalPrice;
        $this->billingAddress = $billingAddress;
        $this->creationDate = $creationDate;
        $this->accepted = $accepted;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return array
     */
    public function getOrderItems(): array
    {
        return $this->orderItems;
    }

    /**
     * @return int|null
     */
    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    /**
     * @return AddressDTO
     */
    public function getBillingAddress(): AddressDTO
    {
        return $this->billingAddress;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreationDate(): DateTimeImmutable
    {
        return $this->creationDate;
    }

    /**
     * @return bool|null
     */
    public function getAccepted(): ?bool
    {
        return $this->accepted;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'orderItems' => array_map(
                function(ItemDTO $item){
                    return $item->toArray();
                },
                $this->orderItems
            ),
            'totalPrice' => $this->totalPrice,
            'billingAddress' => $this->billingAddress->toArray(),
            'creationDate' => $this->creationDate,
            'accepted' => $this->accepted,
        ];
    }
}
