<?php

namespace App\Observers;

use App\Models\Cart;
use App\Services\LogInterface;

class CartObserver
{

    public function __construct(protected LogInterface $logger) {}

    /**
     * @param Cart $cart
     * @return void
     */
    public function created(Cart $cart) : void
    {
        $this->logger->cartCreated($cart);
    }

    public function deleted(Cart $cart) : void
    {
        $this->logger->cartDeleted($cart);
    }
}
