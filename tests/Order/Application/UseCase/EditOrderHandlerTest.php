<?php

namespace App\Tests\Order\Application\UseCase;

use App\Order\Application\UseCase\EditOrder\EditOrderHandlerMysql;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Repository\OrderRepository;
use App\Order\Domain\ValueObject\Address;
use Exception;
use PHPUnit\Framework\TestCase;

class EditOrderHandlerTest extends TestCase
{
    private const VALID_DATA = [
        'user_id' => '123',
        'order_items' => [
            [
                'description' => 'test'
            ]
        ],
        'billing_address' => [
            'first_name' => 'test',
            'last_name' => 'test',
            'street_name' => 'test',
            'city_name' => 'test',
            'postal_code' => 'test',
        ],
    ];

    private const INVALID_DATA = [
        'user_id' => '123'
    ];

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

        $useCase = new EditOrderHandlerMysql($repository);

        $result = $useCase->handle(123, self::VALID_DATA);

        $this->assertEquals(200, $result->getStatus());
    }

    /**
     * @test
     */
    public function shouldReturnErrorOnDbException(): void
    {
        $repository = $this->createMock(OrderRepository::class);
        $repository->method('insertOrder')->willThrowException(new Exception());

        $useCase = new EditOrderHandlerMysql($repository);

        $result = $useCase->handle(123, self::INVALID_DATA);

        $this->assertEquals(500, $result->getStatus());
    }
}