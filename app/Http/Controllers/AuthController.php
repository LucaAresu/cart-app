<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends AbstractApiController
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function register() : \Illuminate\Http\JsonResponse
    {
        $data = request()->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'password' => $data['password'],
            'email' => $data['email']
        ]);
        Auth::attempt($data);

        return $this->getValidResponse(
            [
                'token' => $user->createToken('API Token')->plainTextToken,
                'user' => $user
            ]
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function login() : \Illuminate\Http\JsonResponse
    {
        $data = request()->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:6']
        ]);

        if (!Auth::attempt($data)) {
            return response()->json(['error' => 'Login Failed'], Response::HTTP_BAD_REQUEST);
        }

        return $this->getValidResponse(
            [
                'token' => auth()->user()->createToken('API Token')->plainTextToken,
                'user' => auth()->user()
            ]
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() : \Illuminate\Http\JsonResponse
    {
        auth()->user()->tokens()->delete();

        return $this->getValidResponse(['status' => 'OK', 'message' => 'Logged Out']);
    }
}
