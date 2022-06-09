<?php

namespace App\Tests\Order\Application\UseCase;

use App\Order\Application\UseCase\DeleteOrder\DeleteOrderHandlerMysql;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Repository\OrderRepository;
use App\Order\Domain\ValueObject\Address;
use Exception;
use PHPUnit\Framework\TestCase;

class DeleteOrderHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnOk(): void
    {
        $repository = $this->createMock(OrderRepository::class);
        $repository->method('get')->willReturn(
            new Order(
                123,
                123,
                [['description' => 'test']],
                new Address(
                    'test', 'test', 'test', 'test', 'test'
                )
            )
        );
        $useCase = new DeleteOrderHandlerMysql($repository);

        $result = $useCase->handle(123);

        $this->assertEquals(200, $result->getStatus());
    }

    /**
     * @test
     */
    public function shouldReturnErrorWhenOrderNotFound(): void
    {
        $repository = $this->createMock(OrderRepository::class);
        $repository->method('get')->willThrowException(new Exception());

        $useCase = new DeleteOrderHandlerMysql($repository);

        $result = $useCase->handle(1);

        $this->assertEquals(500, $result->getStatus());
    }
}