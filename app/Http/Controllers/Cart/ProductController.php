<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\AbstractApiController;
use App\Models\Cart;
use App\Models\Product;
use App\Services\Cart\GetProductIds;
use App\Services\CartInterface;
use Illuminate\Http\JsonResponse;

class ProductController extends AbstractApiController
{

    use GetProductIds;

    public function __construct(protected CartInterface $cart) {}

    /**
     * @return JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function addProducts() : JsonResponse
    {
        $data = request()->validate([
            'cart' => ['required', 'exists:carts,id,user_id,' . auth()->user()->id],
            'skus' => ['required', 'array', 'min:1'],
            'skus.*' => ['required', 'exists:products,sku']
        ]);

        $cart = Cart::findOrFail($data['cart']);

        $this->cart->addProducts($cart, $this->getProductIds($data['skus']));

        return $this->getValidResponse(['message' => 'Product added successfully']);
    }


    /**
     * @return JsonResponse
     */
    public function removeProducts() : JsonResponse
    {
        $data = request()->validate([
            'cart' => ['required', 'exists:carts,id,user_id,' . auth()->user()->id],
            'skus' => ['required', 'array', 'min:1'],
            'skus.*' => ['required', 'exists:products,sku']
        ]);

        $cart = Cart::findOrFail($data['cart']);

        $this->cart->removeProducts($cart, $this->getProductIds($data['skus']));

        return $this->getValidResponse(['message' => 'Product removed successfully']);
    }
}
