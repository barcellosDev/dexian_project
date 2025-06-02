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
            'name' => 'cliente' . md5( (string) microtime() ) . sha1(random_int(PHP_INT_MIN, PHP_INT_MAX)),
            'email' => 'cliente'. md5( (string) microtime() ) . sha1(random_int(PHP_INT_MIN, PHP_INT_MAX)) . ' @gmail.com',
            'call_number' => '23123',
            'birth_date' => '2000-08-10',
            'address' => 'rua tal',
            'address_complement' => 'casa',
            'neighborhood' => 'bairro x',
            'postal_address_code' => '12312321312'
        ];
    }
}
