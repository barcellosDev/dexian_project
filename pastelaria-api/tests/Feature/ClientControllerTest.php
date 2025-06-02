<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_a_client()
    {
        $authClient = Client::factory()->create();
        $this->actingAs($authClient, 'sanctum');

        $clientData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'call_number' => '11999999999',
            'birth_date' => '1990-01-01',
            'address' => '123 Street Name',
            'address_complement' => 'Apartment 4B',
            'neighborhood' => 'Downtown',
            'postal_address_code' => '12345678',
        ];

        $response = $this->postJson('/api/clients', $clientData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('clients', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
            'call_number' => '11999999999',
        ]);
    }

    public function test_authenticated_client_can_update_their_profile()
    {
        $client = Client::factory()->create();
        $this->actingAs($client, 'sanctum');

        $base_payload = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'call_number' => '123456789',
            'birth_date' => '1990-01-01',
            'address' => '123 Updated Street',
            'address_complement' => 'Apt 10',
            'neighborhood' => 'Updated Neighborhood',
            'postal_address_code' => '12345-678',
        ];
        $updatedData = [
            'client_data' => json_encode($base_payload)
        ];

        $response = $this->putJson("/api/clients/{$client->id}", $updatedData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('clients', array_merge(['id' => $client->id], $base_payload));

        $client->refresh();
        foreach ($base_payload as $key => $value) {
            $this->assertEquals($value, $client->$key);
        }
    }

    public function test_authenticated_client_can_be_deleted_with_orders_and_products()
    {
        $client = Client::factory()->create();
        $this->actingAs($client, 'sanctum');

        $orders = Order::factory()->count(2)->create(['client_id' => $client->id]);
        $products = Product::factory()->count(3)->create();

        foreach ($orders as $order) {
            $order->products()->attach($products->pluck('id')->toArray());
        }

        $response = $this->deleteJson("/api/clients/{$client->id}");

        $response->assertStatus(200);

        $this->assertSoftDeleted('clients', ['id' => $client->id]);

        foreach ($orders as $order) {
            $this->assertSoftDeleted('orders', ['id' => $order->id]);
        }

        foreach ($orders as $order) {
            foreach ($products as $product) {
                $this->assertSoftDeleted('order_product', [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                ]);
            }
        }

        foreach ($products as $product) {
            $this->assertSoftDeleted('products', ['id' => $product->id]);
        }
    }
}
