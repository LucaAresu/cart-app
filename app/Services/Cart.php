<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Cart implements CartInterface
{

    /**
     * @param string $name
     * @param string|int $userId
     * @throws \Illuminate\Validation\ValidationException
     * @return \App\Models\Cart
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
     * @param int|string $cartId
     * @param string|int $userId
     * @throws \Illuminate\Validation\ValidationException
     * @return bool
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

    /**
     * @param \App\Models\Cart $cart
     * @param array $productIds
     * @throws \Illuminate\Validation\ValidationException
     * @return \App\Models\Cart
     */
    public function addProducts(\App\Models\Cart $cart, array $productIds): \App\Models\Cart
    {

        Validator::validate(['productIds' => $productIds],
            [
                'productIds' => ['required', 'array', 'min:1'],
                'productIds.*' => ['required', 'exists:products,id,deleted_at,NULL']
            ]
        );

        $cartProductIds = $cart->products()->get()->map(fn($product) => $product->id)->toArray();
        $productIds = array_filter(
            callback: fn($id) => (! in_array($id, $cartProductIds)),
            array: $productIds
        );

        $cart->products()->attach($productIds);
        return $cart;
    }

    /**
     * @inheritdoc
     */
    public function removeProducts(\App\Models\Cart $cart, array $productIds): \App\Models\Cart
    {
        $cart->products()->detach($productIds);
        return $cart;
    }
}
