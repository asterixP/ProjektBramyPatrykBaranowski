<?php

namespace App\Order\Domain\ValueObject;

final class Item
{
    private string $description;

    /**
     * @param string $description
     */
    public function __construct(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
