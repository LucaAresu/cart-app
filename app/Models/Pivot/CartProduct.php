<?php

namespace App\Models\Pivot;

use App\Services\LogInterface;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CartProduct extends Pivot
{
    protected static ?LogInterface $logger = null;


    /**
     * @return LogInterface
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected static function getLogger() : LogInterface
    {
        if (!static::$logger) {
            static::$logger = app()->make(LogInterface::class);
        }
        return static::$logger;
    }

    protected static function booted()
    {
        parent::booted();
        static::created(function (Pivot $pivot) {
            /** @var LogInterface $logger */
            $logger = static::getLogger();
            $logger->productAdded(cartId: $pivot->cart_id, productId: $pivot->product_id);
        });

        static::deleted(function (Pivot $pivot) {
            $logger = static::getLogger();
            $logger->productRemove(cartId: $pivot->cart_id, productId: $pivot->product_id);
        });
    }

}
