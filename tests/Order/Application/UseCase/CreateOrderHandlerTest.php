<?php

namespace App\Tests\Order\Application\UseCase;

use App\Order\Application\UseCase\CreateOrder\CreateOrderHandlerMysql;
use App\Order\Domain\Repository\OrderRepository;
use Exception;
use PHPUnit\Framework\TestCase;

class CreateOrderHandlerTest extends TestCase
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
    public function canValidateProperData(): void
    {
        $useCase = new CreateOrderHandlerMysql($this->createMock(OrderRepository::class));

        $result = $useCase->handle(self::VALID_DATA);

        $this->assertEquals(201, $result->getStatus());
    }

    /**
     * @test
     */
    public function shouldReturnErrorOnInvalidData(): void
    {
        $useCase = new CreateOrderHandlerMysql($this->createMock(OrderRepository::class));

        $result = $useCase->handle(self::INVALID_DATA);

        $this->assertEquals(400, $result->getStatus());
    }

    /**
     * @test
     */
    public function shouldReturnErrorOnDbException(): void
    {
        $repository = $this->createMock(OrderRepository::class);
        $repository->method('insertOrder')->willThrowException(new Exception());

        $useCase = new CreateOrderHandlerMysql($repository);

        $result = $useCase->handle(self::VALID_DATA);

        $this->assertEquals(500, $result->getStatus());
    }
}