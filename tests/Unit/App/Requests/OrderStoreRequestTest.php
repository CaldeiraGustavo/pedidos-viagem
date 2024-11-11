<?php

namespace Tests\Unit\App\Requests;

use App\Enums\OrderStatus;
use App\Http\Requests\OrderStoreRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tests\TestCase;

class OrderStoreRequestTest extends TestCase
{
    private $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new OrderStoreRequest;
    }

    public function testShouldBeAllRules()
    {
        $expect = [
            'name' => ['required', 'string', 'max:50'],
            'destination' => ['required', 'string', 'max:100'],
            'departure' => ['required', 'date', 'date_format:Y-m-d', 'before:return'],
            'return' => ['required', 'date', 'date_format:Y-m-d', 'after:departure'],
            'status' => ['required', Rule::enum(OrderStatus::class)],
        ];

        $this->assertEquals($expect, $this->request->rules());
    }

    public function testShouldAcceptValidData()
    {
        $validData = self::factoryToTest();

        $validator = Validator::make($validData, $this->request->rules());

        $this->assertTrue($validator->passes());
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
            [[]], //nenhum dado passado
            [//no name
                self::factoryToTest(['name' => null]),
            ],
            [//name too long
                self::factoryToTest(['name' => str_pad('t', 105)]),
            ],
            [//no destination
                self::factoryToTest(['destination' => null]),
            ],
            [//destination too long
                self::factoryToTest(['destination' => str_pad('t', 105)]),
            ],
            [//no departure date
                self::factoryToTest(['departure' => null]),
            ],
            [//departure date invalid
                self::factoryToTest(['departure' => 'null']),
            ],
            [//departure date after return date
                self::factoryToTest(['departure' => Carbon::tomorrow()->format('Y-m-d')]),
            ],
            [//no return date
                self::factoryToTest(['return' => null]),
            ],
            [//return date invalid
                self::factoryToTest(['return' => 'null']),
            ],
            [//return date before departure date
                self::factoryToTest(['return' => Carbon::yesterday()->format('Y-m-d')]),
            ],
            [//no status
                self::factoryToTest(['status' => null]),
            ],
            [//status invalid
                self::factoryToTest(['status' => fake()->name()]),
            ],
        ];
    }

    private static function factoryToTest($overrides = [])
    {
        $defaults = [
            'name' => fake()->name(),
            'destination' => fake()->name(),
            'departure' => Carbon::now()->format('Y-m-d'),
            'return' => Carbon::tomorrow()->format('Y-m-d'),
            'status' => fake()->randomElement(array_column(OrderStatus::cases(), 'value')),
        ];

        return array_merge($defaults, $overrides);
    }
}
