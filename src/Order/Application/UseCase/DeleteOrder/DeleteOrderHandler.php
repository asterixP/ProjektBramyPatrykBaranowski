<?php

namespace App\Order\Application\UseCase\DeleteOrder;

use App\Common\Application\Payload\ApiPayload;

interface DeleteOrderHandler
{
    public function handle(int $orderId): ApiPayload;
}