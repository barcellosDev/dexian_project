<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::create([
            'name' => 'cliente 1',
            'email' => 'cliente1@gmail.com',
            'call_number' => '23123',
            'birth_date' => '2000-08-10',
            'address' => 'rua tal',
            'address_complement' => 'casa',
            'neighborhood' => 'bairro x',
            'postal_address_code' => '12312321312'
        ]);
    }
}
