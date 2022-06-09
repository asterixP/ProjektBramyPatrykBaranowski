<?php

namespace App\Order\Application\Query;

use Doctrine\DBAL\Exception;

interface GetNotRemovedOrdersQuery
{
    /**
     * @throws Exception
     */
    public function execute(): array;
}