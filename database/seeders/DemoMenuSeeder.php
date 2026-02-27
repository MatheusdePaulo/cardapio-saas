<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class DemoMenuSeeder extends Seeder
{
    public function run(): void
    {
        $restaurant = Restaurant::where('slug', 'burguer-na-brasa')->firstOrFail();

        $burgers = Category::firstOrCreate(
            ['restaurant_id' => $restaurant->id, 'slug' => 'burgers'],
            ['name' => 'Hambúrgueres', 'sort_order' => 1, 'is_active' => true]
        );

        $drinks = Category::firstOrCreate(
            ['restaurant_id' => $restaurant->id, 'slug' => 'bebidas'],
            ['name' => 'Bebidas', 'sort_order' => 2, 'is_active' => true]
        );

        Product::firstOrCreate(
            ['restaurant_id' => $restaurant->id, 'slug' => 'smash-classico'],
            [
                'category_id' => $burgers->id,
                'name' => 'Smash Clássico',
                'description' => 'Pão, carne smash, queijo, cebola e molho da casa.',
                'price_cents' => 1890,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        Product::firstOrCreate(
            ['restaurant_id' => $restaurant->id, 'slug' => 'double-cheddar'],
            [
                'category_id' => $burgers->id,
                'name' => 'Double Cheddar',
                'description' => 'Dois smash, cheddar cremoso e bacon.',
                'price_cents' => 2590,
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        Product::firstOrCreate(
            ['restaurant_id' => $restaurant->id, 'slug' => 'coca-lata'],
            [
                'category_id' => $drinks->id,
                'name' => 'Coca-Cola Lata',
                'description' => '350ml',
                'price_cents' => 650,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );
    }
}
