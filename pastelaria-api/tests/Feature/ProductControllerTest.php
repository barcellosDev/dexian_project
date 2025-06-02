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

    public function test_authenticated_client_can_update_product_with_photo()
    {
        // Fake the storage disk
        Storage::fake('public');

        // Authenticate a client
        $client = Client::factory()->create();
        $this->actingAs($client, 'sanctum');

        $file = new UploadedFile(
            'tests/Feature/assets/TESTE_IMAGEM.jpg', // absolute path
            'TESTE_IMAGEM.jpg', // original filename
            'image/jpeg', // mime type
            null, // size (null auto-detects)
            true // test mode = skip file existence checks
        );

        // Create a product to update
        $product = Product::factory()->create([
            'name' => 'Old Name',
            'price' => 10.00,
            'photo_src' => null,
        ]);

        // Prepare updated data
        $newData = [
            'name' => 'Updated Product',
            'price' => 25.99,
            'photo' => $file,
        ];

        // Send the request
        $response = $this->putJson("/api/products/{$product->id}", $newData);

        // Assert successful response
        $response->assertStatus(200);

        // Assert updated in database
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'price' => 25.99,
        ]);

        // Assert new file stored
        Storage::disk('public')->assertExists("images/" . $file->hashName());
    }

    public function test_authenticated_client_can_soft_delete_product_and_delete_file()
    {
        Storage::fake('public');

        $client = Client::factory()->create();
        $this->actingAs($client, 'sanctum');

        $file = new UploadedFile(
            'tests/Feature/assets/TESTE_IMAGEM.jpg',
            'TESTE_IMAGEM.jpg',
            'image/jpeg',
            null,
            true
        );

        $path = $file->store('images', 'public');

        $product = Product::factory()->create([
            'photo_src' =>  "http://localhost:8000/storage/" . $path,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->deleteJson("/api/products/{$product->id}");
        $response->assertStatus(200);

        $this->assertSoftDeleted('products', ['id' => $product->id]);

        Storage::disk('public')->assertMissing("images/" . $path);
    }
}
