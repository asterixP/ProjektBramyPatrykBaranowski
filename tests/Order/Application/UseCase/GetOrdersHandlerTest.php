<?php

namespace App\Tests\Order\Application\UseCase;

use App\Order\Application\DTO\AddressDTO;
use App\Order\Application\DTO\ItemDTO;
use App\Order\Application\DTO\OrderDTO;
use App\Order\Application\Query\GetNotRemovedOrdersQuery;
use App\Order\Application\UseCase\GetOrders\GetOrdersHandlerMysql;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;

class GetOrdersHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnErrorOnDbException(): void
    {
        $query = $this->createMock(GetNotRemovedOrdersQuery::class);
        $query->method('execute')->willThrowException(new Exception());

        $useCase = new GetOrdersHandlerMysql($query);
        $result = $useCase->handle();

        $this->assertEquals(500, $result->getStatus());
    }

    /**
     * @test
     */
    public function shouldReturnProperObject(): void
    {
        $testOrder = new OrderDTO(
            123,
            123,
            [new ItemDTO('test')],
            0,
            new AddressDTO('test', 'test', 'test', 'test', 'test'),
            new DateTimeImmutable(),
            false
        );

        $expectedResult = [
            'message' => 'Ok',
            'orders' => array_map(
                function (OrderDTO $order) {
                    return $order->toArray();
                },
                [$testOrder]
            )
        ];

        $query = $this->createMock(GetNotRemovedOrdersQuery::class);
        $query->method('execute')->willReturn([$testOrder]);

        $useCase = new GetOrdersHandlerMysql($query);
        $result = $useCase->handle();

        $this->assertEquals($expectedResult, $result->getData());
    }
}