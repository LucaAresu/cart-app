<?php

namespace Tests\Feature\Cart;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends \Tests\TestCase
{

    use RefreshDatabase;

    const ROUTE = '/api/V1/cart/products';

    public function testAddProductToCart()
    {
        $user = User::factory()->hasCarts(1)->create();
        $cart = $user->carts()->first();

        $product = Product::factory()->create();

        $response = $this->actingAs($user)
            ->post(self::ROUTE, [
                'cart' => $cart->id,
                'skus' => [$product->sku]
            ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 'OK']);
        $this->assertDatabaseHas('cart_product', [
            'cart_id' => $cart->id,
            'product_id' => $product->id
        ]);
    }

    public function testAddProductToCartDontExist()
    {
        $user = User::factory()->hasCarts(1)->create();
        $cart = $user->carts()->first();

        $response = $this->actingAs($user)
            ->post(self::ROUTE, [
                'cart' => $cart->id,
                'skus' => ['fsafsa']
            ]);

        $response->assertStatus(422);
    }

    public function testAddProductToCartOfAnotherUser()
    {
        $user = User::factory()->hasCarts(1)->create();
        $cart = $user->carts()->first();
        $userWithoutCart = User::factory()->create();

        $product = Product::factory()->create();

        $response = $this->actingAs($userWithoutCart)
            ->post(self::ROUTE, [
                'cart' => $cart->id,
                'skus' => [$product->sku]
            ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('cart_product', [
            'product_id' => $product->id,
            'cart_id' => $cart->id
        ]);
    }

    public function testRemoveProduct()
    {
        User::factory()->create()
            ->each(function ($user) {
                Cart::factory()
                    ->hasProducts(1)
                    ->create(['user_id' => $user->id]);
            });
        $user = User::find(1);
        $cart = $user->carts()->first();
        $product = $cart->products()->first();

        $this->assertDatabaseHas('cart_product', [
            'cart_id' => $cart->id,
            'product_id' => $product->id
        ]);

        $response = $this->actingAs($user)
            ->delete(self::ROUTE, [
                'cart' => $cart->id,
                'skus' => [$product->sku]
            ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 'OK']);
        $this->assertDatabaseMissing('cart_product', [
            'cart_id' => $cart->id,
            'product_id' => $product->id
        ]);
    }

    public function testRemoveProductOfAnotherUser()
    {
        User::factory()->create()
            ->each(function ($user) {
                Cart::factory()
                    ->hasProducts(1)
                    ->create(['user_id' => $user->id]);
            });
        $user = User::find(1);
        $cart = $user->carts()->first();
        $product = $cart->products()->first();

        $userWithoutCart = User::factory()->create();

        $this->assertDatabaseHas('cart_product', [
            'cart_id' => $cart->id,
            'product_id' => $product->id
        ]);

        $response = $this->actingAs($userWithoutCart)
            ->delete(self::ROUTE, [
                'cart' => $cart->id,
                'skus' => [$product->sku]
            ]);

        $response->assertStatus(422);
        $this->assertDatabaseHas('cart_product', [
            'cart_id' => $cart->id,
            'product_id' => $product->id
        ]);
    }
}
