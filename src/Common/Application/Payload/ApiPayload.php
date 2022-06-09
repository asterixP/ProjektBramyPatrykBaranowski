<?php

namespace App\Common\Application\Payload;

final class ApiPayload
{
    private ?array $data;

    private int $status;

    /**
     * @param array|null $data
     * @param int $status
     */
    public function __construct(?array $data, int $status)
    {
        $this->data = $data;
        $this->status = $status;
    }

    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }
}
