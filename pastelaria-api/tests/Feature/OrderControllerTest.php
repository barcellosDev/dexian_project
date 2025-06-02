<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_client_can_create_order_with_products()
    {
        // Create a client and authenticate via Sanctum
        $client = Client::factory()->create();
        $this->actingAs($client, 'sanctum');

        // Create some products
        $products = Product::factory()->count(3)->create();

        // Prepare request payload
        $payload = $products->pluck('id')->toArray();

        // Make the POST request
        $response = $this->postJson('/api/orders', $payload);

        $response->assertStatus(200);

        // Assert order exists for this client
        $this->assertDatabaseHas('orders', [
            'client_id' => $client->id,
        ]);

        // Fetch order from DB to check relationships
        $order = Order::first();

        // Assert the order has exactly the attached products
        $this->assertEqualsCanonicalizing(
            $products->pluck('id')->toArray(),
            $order->products->pluck('id')->toArray()
        );
    }
}
