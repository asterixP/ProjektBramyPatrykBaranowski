<?php

namespace App\Order\Application\UseCase\EditOrder;

use App\Common\Application\Payload\ApiPayload;

interface EditOrderHandler
{
    public function handle(int $orderId, array $data): ApiPayload;
}