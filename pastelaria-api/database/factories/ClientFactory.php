<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'cliente ' . random_int(1, 100),
            'email' => 'cliente '. random_int(1, 100) .' @gmail.com',
            'call_number' => '23123',
            'birth_date' => '2000-08-10',
            'address' => 'rua tal',
            'address_complement' => 'casa',
            'neighborhood' => 'bairro x',
            'postal_address_code' => '12312321312'
        ];
    }
}
