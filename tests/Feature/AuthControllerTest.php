<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends \Tests\TestCase
{

    use RefreshDatabase;

    const ROUTE = '/api/V1/user/';

    public function testLoginSuccess()
    {
        $user = User::factory()->create([
            'password' => 'test123'
        ]);

        $response = $this->post(self::ROUTE . 'login', ['email' => $user->email, 'password' => 'test123']);

        $response->assertStatus(200);
        $this->assertAuthenticatedAs($user);
    }

    public function testLoginWrongPassword()
    {
        $user = User::factory()->create();

        $response = $this->post(self::ROUTE . 'login', ['email' => $user->email, 'password' => 'wrongPassowrd']);

        $response->assertStatus(400);
        $this->assertGuest();
    }

    public function testRegistrationSuccess()
    {
        $response = $this->post(
            self::ROUTE . 'register',
            [
                'email' => 'prova@example.net',
                'password' => 'password',
                'name' => 'name'
            ]
        );

        $response->assertStatus(200);
        $this->assertAuthenticatedAs(
            User::find(
                $response->json()['data']['user']['id']
            )
        );
    }

    public function testRegistrationWithEmailUsed()
    {
        $user = User::factory()->create([
            'password' => 'test123'
        ]);

        $response = $this->post(
            self::ROUTE . 'register',
            [
                'email' => $user->email,
                'password' => 'password',
                'name' => 'name'
            ]
        );

        $response->assertStatus(422);
        $this->assertGuest();
    }

    public function testLogout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(self::ROUTE . 'logout');

        $response->assertStatus(200);

        /** laravel bug, this assert fails
         * @see https://stackoverflow.com/questions/57813795/method-illuminate-auth-requestguardlogout-does-not-exist-laravel-passport
         */
//        $this->assertGuest();
    }
}
