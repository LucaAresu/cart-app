<?php

namespace App\Services\Cart;

use App\Models\Product;

trait GetProductIds
{
    protected function getProductIds(array $skus) : array
    {
        return Product::all()
            ->whereIn('sku', $skus)
            ->map(fn($product) => $product->id)
            ->toArray();
    }
}
