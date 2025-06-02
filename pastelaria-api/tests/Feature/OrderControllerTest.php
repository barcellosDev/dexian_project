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
        $client = Client::factory()->create();
        $this->actingAs($client, 'sanctum');

        $products = Product::factory()->count(3)->create();

        $payload = $products->pluck('id')->toArray();

        $response = $this->postJson('/api/orders', $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'client_id' => $client->id,
        ]);

        $order = Order::first();

        $this->assertEqualsCanonicalizing(
            $products->pluck('id')->toArray(),
            $order->products->pluck('id')->toArray()
        );
    }

    public function test_authenticated_client_can_update_order_products()
    {
        $client = Client::factory()->create();
        $this->actingAs($client, 'sanctum');

        $initialProducts = Product::factory()->count(2)->create();
        $updatedProducts = Product::factory()->count(3)->create();

        $order = Order::factory()->create(['client_id' => $client->id]);
        $order->products()->attach($initialProducts->pluck('id')->toArray());

        $this->assertCount(2, $order->products);

        $expectedResult = $updatedProducts->pluck('id')->toArray();

        $payload = [
            'products' => json_encode($expectedResult),
        ];

        $response = $this->putJson("/api/orders/{$order->id}", $payload);
        $response->assertStatus(200);

        $order->refresh();

        $actualData = $order->products()->pluck('product_id')->toArray();

        $this->assertEqualsCanonicalizing(
            $expectedResult,
            $actualData
        );
    }

    public function test_authenticated_client_can_soft_delete_order_products_and_pivot()
    {
        $client = Client::factory()->create();
        $this->actingAs($client, 'sanctum');

        $products = Product::factory()->count(3)->create();
        $order = Order::factory()->create(['client_id' => $client->id]);

        foreach ($products as $product) {
            $order->products()->attach($product->id);
        }

        $response = $this->deleteJson("/api/orders/{$order->id}");
        $response->assertStatus(200);

        $this->assertSoftDeleted('orders', ['id' => $order->id]);

        foreach ($products as $product) {
            $this->assertSoftDeleted('products', ['id' => $product->id]);
            $this->assertNotNull(Product::withTrashed()->find($product->id));
        }

        foreach ($products as $product) {
            $this->assertSoftDeleted('order_product', [
                'order_id' => $order->id,
                'product_id' => $product->id,
            ]);
        }

        $this->assertNotNull(Order::withTrashed()->find($order->id));
    }
}
