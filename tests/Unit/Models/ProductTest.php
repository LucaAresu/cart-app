<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends \Tests\TestCase
{
    use RefreshDatabase;

    public function testCarts()
    {
        $user = User::factory()->hasCarts(1)->create();
        $product = Product::factory()->create();
        $cart = $user->carts()->first();

        $product->carts()->attach($cart);

        $this->assertDatabaseHas('cart_product', [
            'product_id' => $product->id,
            'cart_id' => $cart->id
        ]);
    }

}
