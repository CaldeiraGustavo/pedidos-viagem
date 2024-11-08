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
            'departure' => ['required', 'date', 'before:return'],
            'return' => ['required', 'date', 'after:departure'],
            'status' => ['required', Rule::enum(OrderStatus::class)],
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
                self::factoryToTest(['departure' => Carbon::tomorrow()->format('d/m/Y')]),
            ],
            [//no return date
                self::factoryToTest(['return' => null]),
            ],
            [//return date invalid
                self::factoryToTest(['return' => 'null']),
            ],
            [//return date before departure date
                self::factoryToTest(['return' => Carbon::yesterday()->format('d/m/Y')]),
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
            'departure' => Carbon::now()->format('d/m/Y'),
            'return' => Carbon::tomorrow()->format('d/m/Y'),
            'status' => fake()->randomElement(array_column(OrderStatus::cases(), 'value')),
        ];

        return array_merge($defaults, $overrides);
    }
}
