<?php

namespace App\Services;

use App\Models\Log;
use App\Models\Product;

interface LogInterface
{
    /**
     * @param \App\Models\Cart $cart
     * @return Log
     */
    public function cartCreated(\App\Models\Cart $cart) : Log;

    /**
     * @param \App\Models\Cart $cart
     * @return Log
     */
    public function cartDeleted(\App\Models\Cart $cart) : Log;

    /**
     * @param \App\Models\Cart $cart
     * @return Log
     */
    public function productAdded(int|string $cartId, int|string $product) : Log;

    /**
     * @param \App\Models\Cart $cart
     * @return Log
     */
    public function productRemove(int|string $cart, int|string $product) : Log;

}
