<?php

namespace Tests\Unit\App\Requests;

use App\Enums\OrderStatus;
use App\Http\Requests\OrderShowRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tests\TestCase;

class OrderShowRequestTest extends TestCase
{
    private $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new OrderShowRequest;
    }

    public function testShouldBeAllRules()
    {
        $expect = [
            'status' => [Rule::enum(OrderStatus::class)],
        ];

        $this->assertEquals($expect, $this->request->rules());
    }

    public function testShouldAcceptValidData()
    {
        $validData = ['status' => fake()->randomElement(array_column(OrderStatus::cases(), 'value'))];

        $validator = Validator::make($validData, $this->request->rules());

        $this->assertTrue($validator->passes());
    }

    public function testAutorizeTrue()
    {
        $this->assertEquals(true, $this->request->authorize());
    }

    public function testShouldNotAcceptInvalidData()
    {
        $validData = ['status' => 'INVALID_STATUS'];

        $validator = Validator::make($validData, $this->request->rules());

        $this->assertTrue($validator->fails());
    }
}
