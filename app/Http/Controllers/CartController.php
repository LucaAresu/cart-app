<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Services\Cart\GetProductIds;
use App\Services\CartInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CartController extends AbstractApiController
{

    use GetProductIds;

    public function __construct(protected CartInterface $cart) {}

    /**
     * @return JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create() : JsonResponse
    {
        request()->validate([
            'name' => 'required',
            'skus' => ['required', 'array', 'min:1'],
            'skus.*' => ['required', 'exists:products,sku']
        ]);

        $cart = $this->cart->create(request()->get('name'), auth()->user()->id);

        $this->cart->addProducts($cart, $this->getProductIds(request()->get('skus')));

        return $this->getValidResponse(['id' => $cart->id]);
    }


    /**
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete()  : JsonResponse
    {
        $data = request()->validate([
            'cart' => ['required', 'exists:carts,id,user_id,' . auth()->user()->id],
        ]);

        $this->cart->delete($data['cart'], auth()->user()->id);

        return $this->getValidResponse(['message' => 'Cart deleted successfully']);
    }

    public function show(Cart $cart) : JsonResponse
    {
        Validator::validate(
            ['cart' => $cart->id],
            ['cart' => ['required', 'exists:carts,id,user_id,' . auth()->user()->id]]
        );

        $cart->load('products')
            ->makeHidden(['deleted_at']);

        return $this->getValidResponse(['cart' => $cart]);
    }

    public function getList() : JsonResponse
    {
        $carts = Cart::with('products')
            ->whereUserId(auth()->user()->id)
                ->get();
        return $this->getValidResponse(['carts' => $carts]);
    }

}
