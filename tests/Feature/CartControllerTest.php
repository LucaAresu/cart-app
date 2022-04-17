<?php


namespace Tests\Feature;

use App\Http\Controllers\AbstractApiController;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    const ROUTE = '/api/V1/cart';
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreationSuccessful()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $response = $this->actingAs($user)
            ->post(
                self::ROUTE . '/',
                [
                    'name' => 'boh',
                    'skus' => [$product->sku]
                ]
            );

        $response->assertStatus(200)->assertJson([
            'status' => AbstractApiController::STATUS_OK,
            'data' => [
                'id' => 1
            ]
        ]);
        $this->assertDatabaseHas('carts', ['id' => 1]);
    }

    public function testWithEmptyName()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(self::ROUTE . '/', ['name' => '']);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors' => []
        ]);
    }

    public function testWithNoProduct()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(self::ROUTE . '/', ['name' => 'fsafasf']);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors' => []
        ]);
    }

    public function testWithProductNoExist()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(self::ROUTE . '/', ['name' => 'fsafasf', 'skus' => ['fsafas']]);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors' => []
        ]);
    }

    public function testWithNoName()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(self::ROUTE . '/');
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors' => []
        ]);
    }

    public function testDeleteSucess()
    {
        $user = User::factory()->hasCarts()->create();
        $cart = $user->carts()->first();

        $this->assertModelExists($cart);
        $response = $this->actingAs($user)
            ->delete(self::ROUTE . '/', ['cart' => $cart->id]);

        $response->assertStatus(200);
        $this->assertSoftDeleted($cart);
    }

    public function testDeleteFail()
    {
        $user = User::factory()->hasCarts()->create();
        $cart = $user->carts()->first();
        $userNoCart = User::factory()->create();

        $response = $this->actingAs($userNoCart)
            ->delete(self::ROUTE . '/', ['cart' => $cart->id]);

        $response->assertStatus(422);
        $this->assertModelExists($cart);
    }

    public function getCart()
    {
        User::factory()->create()
            ->each(function ($user) {
                Cart::factory()
                    ->hasProducts(1)
                    ->create(['user_id' => $user->id]);
            });
        $user = User::find(1);


        $cart = $user->carts()->first();
        $cart->load('products')->makeHidden('deleted_at');
        $response = $this->actingAs($user)
            ->get(self::ROUTE .'/' . $cart->id);

        $response->assertStatus(200);
        $response->assertSimilarJson([
            'status' => 'OK',
            'data' => [
                'cart' => $cart->toArray()
            ]
        ]);
    }

    public function testGetList()
    {
        User::factory()->create()
            ->each(function ($user) {
                Cart::factory()
                    ->hasProducts(1)
                    ->create(['user_id' => $user->id]);
            });
        $user = User::find(1);


        $response = $this->actingAs($user)
            ->get(self::ROUTE . 's');

        $response->assertStatus(200);
        $response->assertSimilarJson([
            'status' => 'OK',
            'data' => [
                'carts' => $user->carts()->with('products')->get()->toArray()
            ]
        ]);
    }

    public function testGetListEmpty()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(self::ROUTE . 's');

        $response->assertStatus(200);
        $response->assertSimilarJson([
            'status' => 'OK',
            'data' => [
                'carts' => []
            ]
        ]);
    }

    public function testGetCart()
    {
        User::factory()->create()
            ->each(function ($user) {
                Cart::factory()
                    ->hasProducts(1)
                    ->create(['user_id' => $user->id]);
            });
        $user = User::find(1);
        $cart = $user->carts()->first();
        $cart->load('products')
            ->makeHidden(['deleted_at']);

        $response = $this->actingAs($user)
            ->get(self::ROUTE . '/' . $cart->id);

        $response->assertStatus(200);
        $response->assertSimilarJson([
            'status' => 'OK',
            'data' => [
                'cart' => $cart->toArray()
            ]
        ]);
    }

    public function testGetCartNoExist()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(self::ROUTE . '/1');

        $response->assertStatus(404);
    }

    public function testGetCartOfAnotherUser()
    {
        User::factory()->create()
            ->each(function ($user) {
                Cart::factory()
                    ->hasProducts(1)
                    ->create(['user_id' => $user->id]);
            });
        $user = User::find(1);
        $cart = $user->carts()->first();
        $userWithoutCart = User::factory()->create();

        $response = $this->actingAs($userWithoutCart)
            ->get(self::ROUTE . '/' . $cart->id);

        $response->assertStatus(422);
    }

}
