<?php

namespace App\Services;

use App\Models\Product;

class Log implements LogInterface
{

    /**
     * @inheritDoc
     */
    public function cartCreated(\App\Models\Cart $cart): \App\Models\Log
    {
        return \App\Models\Log::create([
            'user_id' => $cart->user->id,
            'cart_id' => $cart->id,
            'action' => \App\Models\Log::ACTION_CREATE
        ]);
    }

    /**
     * @inheritDoc
     */
    public function cartDeleted(\App\Models\Cart $cart): \App\Models\Log
    {
        return \App\Models\Log::create([
            'user_id' => $cart->user->id,
            'cart_id' => $cart->id,
            'action' => \App\Models\Log::ACTION_DELETE
        ]);
    }

    /**
     * @inheritDoc
     */
    public function productAdded(\App\Models\Cart|string|int $cartId, Product|string|int $productId): \App\Models\Log
    {
        return \App\Models\Log::create([
            'user_id' => \App\Models\Cart::find($cartId)->user->id,
            'cart_id' => $cartId,
            'product_id' => $productId,
            'action' => \App\Models\Log::ACTION_ADD_PRODUCT
        ]);    }

    /**
     * @inheritDoc
     */
    public function productRemove(\App\Models\Cart|string|int $cartId, Product|string|int $productId): \App\Models\Log
    {
        return \App\Models\Log::create([
            'user_id' => \App\Models\Cart::find($cartId)->user->id,
            'cart_id' => $cartId,
            'product_id' => $productId,
            'action' => \App\Models\Log::ACTION_REMOVE_PRODUCT
        ]);
    }
}
