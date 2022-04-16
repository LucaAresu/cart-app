<?php

namespace Tests\Unit\Models;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends \Tests\TestCase
{

    use RefreshDatabase;

    public function testUser()
    {
        $user = User::factory()->hasCarts(1)->create();
        $cart = Cart::all()->first();

        $this->assertTrue($cart->user->is($user));
    }

}
