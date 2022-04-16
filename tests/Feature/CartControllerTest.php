<?php


namespace Feature;

use App\Http\Controllers\AbstractApiController;
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
        $response = $this->post(self::ROUTE . '/create', ['name' => 'boh']);

        $response->assertStatus(200)->assertJson([
            'status' => AbstractApiController::STATUS_OK,
            'data' => [
                'id' => 1
            ]
        ]);
    }

    public function testWithEmptyName()
    {
        $response = $this->post(self::ROUTE . '/create', ['name' => '']);
        $response->assertStatus(400)->assertJsonStructure([
            'status',
            'message',
            'errors' => []
        ]);
    }

    public function testWithNoName()
    {
        $response = $this->post(self::ROUTE . '/create');
        $response->assertStatus(400)->assertJsonStructure([
            'status',
            'message',
            'errors' => []
        ]);
    }
}
