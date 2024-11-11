<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\OrderStatus;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'destination' => fake()->name(),
            'departure' => Carbon::now()->format('Y-m-d'),
            'return' => Carbon::tomorrow()->format('Y-m-d'),
            'status' => fake()->randomElement(array_column(OrderStatus::cases(), 'value')),
        ];
    }
}
