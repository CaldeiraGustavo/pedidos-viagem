<?php

namespace Tests\Unit\App\Requests;

use App\Enums\OrderStatus;
use App\Http\Requests\OrderUpdateRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tests\TestCase;

class OrderUpdateRequestTest extends TestCase
{
    private $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new OrderUpdateRequest;
    }

    public function testShouldBeAllRules()
    {
        $expect = [
            'status' => [
                'required',
                Rule::enum(OrderStatus::class)
                ->only([OrderStatus::APPROVED, OrderStatus::CANCELED])
            ],
        ];

        $this->assertEquals($expect, $this->request->rules());
    }

    public function testShouldAcceptValidData()
    {
        $validData = self::factoryToTest();

        $validator = Validator::make($validData, $this->request->rules());

        $this->assertTrue(! $validator->fails());
    }

    public function testAutorizeTrue()
    {
        $this->assertEquals(true, $this->request->authorize());
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalidDataProvider(array $data)
    {
        $validator = Validator::make($data, $this->request->rules());
        $this->assertTrue($validator->fails());
    }

    public static function invalidDataProvider(): array
    {
        return [
            [//no status
                self::factoryToTest(['status' => null]),
            ],
            [//status invalid
                self::factoryToTest(['status' => fake()->name()]),
            ],
            [//status invalid
                self::factoryToTest(['status' => OrderStatus::REQUESTED]),
            ],
        ];
    }

    private static function factoryToTest($overrides = [])
    {
        $defaults = [
            'status' => fake()->randomElement([OrderStatus::APPROVED, OrderStatus::CANCELED]),
        ];

        return array_merge($defaults, $overrides);
    }
}
