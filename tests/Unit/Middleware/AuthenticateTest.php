<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\Authenticate;
use Illuminate\Http\RedirectResponse;

class AuthenticateTest extends \Tests\TestCase
{

    public function testRedirectTo()
    {
        $method = new \ReflectionMethod(Authenticate::class, 'redirectTo');
        $auth = app()->make(Authenticate::class);

        $request = $this->createMock(\Illuminate\Http\Request::class);
        $request->method('expectsJson');
        $request->willReturn(true);

        $result = $method->invokeArgs($auth, [$request]);

        $this->assertTrue($result instanceof RedirectResponse);
    }

}
