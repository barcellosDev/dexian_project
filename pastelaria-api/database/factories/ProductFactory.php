<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => "pastel " . random_int(1, 1000),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'photo_src' => "http://localhost:8000/storage/images/pastel" . random_int(1, 10) . ".jpg"
        ];

    }
}
