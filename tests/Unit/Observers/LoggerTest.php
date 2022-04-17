<?php

namespace Tests\Unit\Observers;

use App\Models\Cart;
use App\Models\Log;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoggerTest extends \Tests\TestCase
{
    use RefreshDatabase;

    protected function getUserWithCart() : User
    {
        return User::factory()->hasCarts(1)->create();
    }

    public function testCreateCart()
    {
        $user = $this->getUserWithCart();

        $this->assertDatabaseHas('logs', [
            'user_id' => $user->id,
            'cart_id' => $user->carts()->first()->id,
            'action' => Log::ACTION_CREATE
        ]);
    }

    public function testDeleteCart()
    {
        $user = $this->getUserWithCart();
        $cart = $user->carts()->first();
        $cart->delete();

        $this->assertDatabaseHas('logs', [
            'user_id' => $user->id,
            'cart_id' => $cart->id,
            'action' => Log::ACTION_DELETE
        ]);
    }

    public function testAddProduct()
    {
        $user = $this->getUserWithCart();
        $product = Product::factory()->create();
        $cart = $user->carts()->first();

        $cart->products()->attach($product);

        $this->assertDatabaseHas('logs', [
            'user_id' => $user->id,
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'action' => Log::ACTION_ADD_PRODUCT
        ]);
    }

    public function testRemoveProduct()
    {
        $user = $this->getUserWithCart();
        $product = Product::factory()->create();
        $cart = $user->carts()->first();

        $cart->products()->attach($product);
        $cart->products()->detach($product);

        $this->assertDatabaseHas('logs', [
            'user_id' => $user->id,
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'action' => Log::ACTION_REMOVE_PRODUCT
        ]);
    }

}
