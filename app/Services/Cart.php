<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Cart implements CartInterface
{


    /**
     * @inheritdoc
     */
    public function create(string $name, string|int $userId): \App\Models\Cart
    {
        Validator::validate(
            compact('name', 'userId'),
            [
                'name' => ['required'],
                'userId' => ['required', 'exists:users,id']
            ]
        );

        return \App\Models\Cart::create([
            'name' => $name,
            'user_id' => $userId
        ]);
    }

    /**
     * @inheritdoc
     */
    public function delete(int|string $cartId, string|int $userId): bool
    {
        Validator::validate(
            compact('cartId', 'userId'),
            [
                // this rule validates only if the cart with id $cartId is owned by user $userId
                'cartId' => ['required', 'exists:carts,id,user_id,' . $userId],
                'userId' => ['required', 'exists:users,id']
            ]
        );
        \App\Models\Cart::findOrFail($cartId)->delete();
        return true;
    }
}
