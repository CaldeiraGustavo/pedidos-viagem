<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Enums\OrderStatus;

class OrderControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testShouldCreateOrderWithValidData()
    {
        $data = [
            'name' => 'Test Order',
            'destination' => 'New York',
            'departure' => '2024-11-15',
            'return' => '2024-11-20',
            'status' => OrderStatus::REQUESTED->value,
        ];


        $response = $this->postJson('/api/orders', $data);
        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', $data);
        $response->assertExactJson(['message' => 'Saved successfully.']);
    }

    public function testShouldShowOrderWithValidId()
    {
        $order = Order::factory()->create();
        $response = $this->getJson("/api/orders/{$order->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'name' => $order->name,
            'destination' => $order->destination,
            'departure' => $order->departure,
            'return' => $order->return,
            'status' => $order->status,
        ]);
    }

    public function testShouldReturns404IfOrderNotFound()
    {
        $nonExistentId = 999;
        $response = $this->getJson("/api/orders/{$nonExistentId}");
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Order not found',
        ]);
    }

    public function testShouldUpdateOrderStatusWithValidId()
    {
        $order = Order::factory()->create([
            'status' => OrderStatus::REQUESTED->value,
        ]);

        $data = [
            'status' => OrderStatus::APPROVED->value,
        ];

        $response = $this->patchJson("/api/orders/{$order->id}/status", $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => OrderStatus::APPROVED->value,
        ]);
        $response->assertJson([
            'status' => OrderStatus::APPROVED->value,
        ]);
    }

    public function testShouldReturns404IfOrderNotFoundWhenUpdatingStatus()
    {
        $nonExistentId = 999;

        $data = [
            'status' => OrderStatus::CANCELED->value,
        ];

        $response = $this->patchJson("/api/orders/{$nonExistentId}/status", $data);
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'Order not found',
        ]);
    }

    public function testShouldListOrdersWithoutFilters()
    {
        Order::factory()->count(20)->create();

        $response = $this->getJson('/api/orders');

        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => ['name', 'status', 'destination', 'departure', 'return']
            ],
            'currentPage',
            'lastPage',
            'perPage',
            'total',
        ]);
    }

    public function testShouldFilterOrdersByStatus()
    {
        Order::factory()->count(10)->create(['status' => OrderStatus::CANCELED->value]);
        Order::factory()->count(5)->create(['status' => OrderStatus::REQUESTED->value]);

        $response = $this->getJson('/api/orders?status=' . OrderStatus::CANCELED->value);

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
        $response->assertJsonFragment(['status' => OrderStatus::CANCELED->value]);
    }

    public function testShouldLimitNumberOfOrdersPerPage()
    {
        Order::factory()->count(30)->create();
        $limit = 10;

        $response = $this->getJson("/api/orders?limit={$limit}");

        $response->assertStatus(200);
        $response->assertJsonCount($limit, 'data');
    }
}
