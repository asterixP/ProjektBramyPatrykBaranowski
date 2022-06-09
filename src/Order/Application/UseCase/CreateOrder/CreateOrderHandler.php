<?php

namespace App\Order\Application\UseCase\CreateOrder;

use App\Common\Application\Payload\ApiPayload;

interface CreateOrderHandler
{
    public function handle(array $data): ApiPayload;
}