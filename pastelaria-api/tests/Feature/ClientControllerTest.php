<?php

namespace Tests\Feature;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_a_client()
    {
        // Authenticate as a client
        $authClient = Client::factory()->create();
        $this->actingAs($authClient, 'sanctum');

        // Data to create a new client
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

        // Send POST request
        $response = $this->postJson('/api/clients', $clientData);

        // Assert 200 OK
        $response->assertStatus(200);

        // Assert client created in DB
        $this->assertDatabaseHas('clients', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
            'call_number' => '11999999999',
        ]);
    }
}
