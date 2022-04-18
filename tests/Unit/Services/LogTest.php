<?php

namespace Tests\Unit\Services;


use App\Models\Log;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogTest extends \Tests\TestCase
{

    use RefreshDatabase;

    public function testCreateCart()
    {
        $user = User::factory()->hasCarts(1)->create();
        $cart = $user->carts()->first();

        $this->assertDatabaseHas('logs', [
            'user_id' => $user->id,
            'cart_id' => $cart->id,
            'action' => Log::ACTION_CREATE
        ]);

        return $cart;
    }


    public function testAddProduct()
    {
        $product = Product::factory()->create();
        $cart = $this->testCreateCart();

        $cart->products()->attach($product);

        $this->assertDatabaseHas('logs', [
            'user_id' => $cart->user->id,
            'cart_id' => $cart->id,
            'product_id' => $cart->products()->first()->id,
            'action' => Log::ACTION_ADD_PRODUCT
        ]);

        return $cart;
    }

    public function testRemoveProduct()
    {
        $cart = $this->testAddProduct();

        $product = $cart->products()->first();

        $cart->products()->detach($product);

        $this->assertDatabaseHas('logs', [
            'user_id' => $cart->user->id,
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'action' => Log::ACTION_REMOVE_PRODUCT
        ]);

        return $cart;
    }

    public function testDeleteCart()
    {
        $cart = $this->testCreateCart();
        $cart->delete();

        $this->assertDatabaseHas('logs', [
            'user_id' => $cart->user->id,
            'cart_id' => $cart->id,
            'action' => Log::ACTION_DELETE
        ]);
    }
}
