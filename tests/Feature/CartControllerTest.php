<?php


namespace Feature;

use App\Http\Controllers\AbstractApiController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    const ROUTE = '/api/cart';
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreationSuccessful()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->post(self::ROUTE . '/', ['name' => 'boh']);

        $response->assertStatus(200)->assertJson([
            'status' => AbstractApiController::STATUS_OK,
            'data' => [
                'id' => 1
            ]
        ]);
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
        $this->assertModelMissing($cart);
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
}
