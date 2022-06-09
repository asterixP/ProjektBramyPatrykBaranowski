<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\Order\Domain\ValueObject\Address;
use App\Order\Domain\ValueObject\Item;
use App\Order\Domain\ValueObject\Price;
use DateTimeImmutable;

final class Order
{
    private int $id;

    private int $userId;

    /**
     * @var array<int,Item>
     */
    private array $orderItems;

    private ?Price $totalPrice;

    private Address $billingAddress;

    private DateTimeImmutable $creationDate;

    private ?bool $accepted;

    private bool $removed;

    /**
     * @param int $id
     * @param int $userId
     * @param array<int,Item> $orderItems
     * @param Address $billingAddress
     * @param Price|null $totalPrice
     * @param DateTimeImmutable|null $creationDate
     * @param bool|null $accepted
     * @param bool $removed
     */
    public function __construct(int $id, int $userId, array $orderItems, Address $billingAddress, ?Price $totalPrice = null, ?DateTimeImmutable $creationDate = null, ?bool $accepted = null, bool $removed = false)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->billingAddress = $billingAddress;
        $this->totalPrice = $totalPrice;
        if (is_null($creationDate)) {
            foreach ($orderItems as $item) {
                $this->orderItems[] = new Item($item['description']);
            }
            $this->creationDate = new DateTimeImmutable();
        } else {
            $this->orderItems = $orderItems;
            $this->creationDate = $creationDate;
        }
        $this->accepted = $accepted;
        $this->removed = $removed;
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
     * @return array<int,Item>
     */
    public function getOrderItems(): array
    {
        return $this->orderItems;
    }

    /**
     * @param array<int,Item> $items
     */
    public function changeOrderItemsTo(array $items): void
    {
        $this->orderItems = [];
        foreach ($items as $item) {
            $this->orderItems[] = new Item($item['description']);
        }
    }

    /**
     * @return Price|null
     */
    public function getTotalPrice(): ?Price
    {
        return $this->totalPrice;
    }

    /**
     * @param Price $totalPrice
     */
    public function changeTotalPriceTo(Price $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return Address
     */
    public function getBillingAddress(): Address
    {
        return $this->billingAddress;
    }

    public function changeBillingAddressTo(Address $address): void
    {
        $this->billingAddress = $address;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreationDate(): DateTimeImmutable
    {
        return $this->creationDate;
    }

    public function remove(): void
    {
        $this->removed = true;
    }

    public function discard(): void
    {
        $this->accepted = false;
    }

    public function accept(): void
    {
        $this->accepted = true;
    }

    public function isAccepted(): ?bool
    {
        return $this->accepted;
    }

    public function isRemoved(): bool
    {
        return $this->removed;
    }
}
