<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoRestaurantSeeder extends Seeder
{
    public function run(): void
    {
        $restaurant = Restaurant::firstOrCreate(
            ['slug' => 'burguer-na-brasa'],
            [
                'name' => 'Burguer na Brasa',
                'phone' => '(88) 99999-9999',
                'is_active' => true,
            ]
        );

        // cria mesas 1..10
        for ($i = 1; $i <= 10; $i++) {
            $restaurant->tables()->firstOrCreate(
                ['number' => $i],
                ['qr_token' => Str::random(32)]
            );
        }
    }
}
