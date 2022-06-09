<?php

namespace App\Order\Application\DTO;

class ItemDTO
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

    public function toArray(): array
    {
        return ['description' => $this->description];
    }
}
