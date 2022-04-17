<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'email' => 'prova@example.net',
            'password' => 'fsamofas'
        ])->each(function ($user) {
            Cart::factory(2)
                ->hasProducts(2)
                ->create([
                'user_id' => $user->id
            ]);
        });

         User::factory(10)
             ->create()->each(function ($user) {
                 Cart::factory(3)
                     ->hasProducts(1)
                     ->create([
                         'user_id' => $user->id
                     ]);
             });

         Product::factory(10)->create();
         Product::factory()->create(['sku' => 'example-sku']);
         Product::factory()->create(['sku' => 'example-sku2']);
    }
}
