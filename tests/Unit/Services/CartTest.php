<?php

namespace Tests\Unit\Services;

use App\Models\Product;
use App\Models\User;
use App\Services\Cart;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->cart = app()->make(Cart::class);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testDeleteCartNotFound()
    {
        $user = User::factory()->create();

        $this->expectException(ValidationException::class);
        $this->cart->delete(1, $user->id);
    }

    public function testDeleteCartOfOtherUser()
    {
        $user1 = User::factory()->hasCarts(1)->create();
        $user2 = User::factory()->create();

        $this->expectException(ValidationException::class);
        $this->cart->delete($user1->carts()->first()->id, $user2->id);
    }

    public function testDeleteCartSuccess()
    {
        $user = User::factory()->hasCarts(1)->create();
        $cart = $user->carts()->first();

        $this->assertDatabaseHas('carts', ['id' => $cart->id]);
        $this->assertEquals(true, $this->cart->delete($cart->id, $user->id));
        $this->assertSoftDeleted($cart);
    }

    public function testCreateCart()
    {
        $user = User::factory()->create();

        $cart = $this->cart->create('name', $user->id);
        $this->assertModelExists($cart);
    }

    public function testAddToCartSuccess()
    {
        $user = User::factory()->hasCarts(1)->create();
        $cart = $user->carts()->first();
        $product = Product::factory()->create();

        $this->cart->addProducts($cart, [$product->id]);

        $this->assertDatabaseHas('cart_product', [
            'cart_id' => $cart->id,
            'product_id' => $product->id
        ]);
    }

    public function testAddToCartProductDontExist()
    {
        $user = User::factory()->hasCarts(1)->create();
        $cart = $user->carts()->first();

        $this->expectException(ValidationException::class);
        $this->cart->addProducts($cart, [1]);
    }

    public function testAddToCartProductSoftDeleted()
    {
        $user = User::factory()->hasCarts(1)->create();
        $cart = $user->carts()->first();
        $product = Product::factory()->create();

        $product->delete();
        $this->assertSoftDeleted($product);

        $this->expectException(ValidationException::class);
        $this->cart->addProducts($cart, [1]);
    }

    public function testRemoveProduct()
    {
        $user = User::factory()->hasCarts(1)->create();
        $product = Product::factory()->create();

        $cart = $user->carts()->first();
        $cart->products()->attach($product);

        $this->assertDatabaseHas('cart_product', [
            'product_id' => $product->id,
            'cart_id' => $cart->id
        ]);
        $this->cart->removeProducts($cart, [$product->id]);
        $this->assertDatabaseMissing('cart_product', [
            'product_id' => $product->id,
            'cart_id' => $cart->id
        ]);
    }

    public function testRemoveProductDontExist()
    {
        $user = User::factory()->hasCarts(1)->create();
        $product = Product::factory()->create();

        $cart = $user->carts()->first();
        $cart->products()->attach($product);

        $this->cart->removeProducts($cart, [$product->id, 2]);
        $this->expectNotToPerformAssertions();
    }
}
