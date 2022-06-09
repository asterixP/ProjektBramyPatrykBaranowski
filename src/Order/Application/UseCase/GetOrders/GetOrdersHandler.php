<?php

namespace App\Order\Application\UseCase\GetOrders;

use App\Common\Application\Payload\ApiPayload;

interface GetOrdersHandler
{
    public function handle(): ApiPayload;
}