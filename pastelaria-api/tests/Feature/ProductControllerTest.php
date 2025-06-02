<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_client_can_create_a_product_with_photo()
    {
        Storage::fake('public');

        // Create and authenticate a Client
        $client = Client::factory()->create();
        $this->actingAs($client, guard: 'sanctum');

        $file = new UploadedFile(
            'tests/Feature/assets/TESTE_IMAGEM.jpg', // absolute path
            'TESTE_IMAGEM.jpg', // original filename
            'image/jpeg', // mime type
            null, // size (null auto-detects)
            true // test mode = skip file existence checks
        );


        $data = [
            'name' => 'Test Product',
            'price' => 99.99,
            'photo' => $file,
        ];

        $response = $this->postJson('/api/products', $data);
        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 99.99,
        ]);

        Storage::disk('public')->assertExists("images/" . $file->hashName());
    }
}
