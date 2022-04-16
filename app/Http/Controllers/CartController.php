<?php

namespace App\Http\Controllers;

use App\Services\CartInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CartController extends AbstractApiController
{

    public function __construct(protected CartInterface $cart) {}

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function create() : \Illuminate\Http\JsonResponse
    {
        request()->validate(['name' => 'required']);

        $cart = $this->cart->create(request()->get('name'), auth()->user()->id);
        return $this->getValidResponse(['id' => $cart->id]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete() // : \Illuminate\Http\JsonResponse
    {
        $data = request()->validate([
            'cart' => ['required', 'exists:carts,id,user_id,' . auth()->user()->id],
        ]);

        $this->cart->delete($data['cart'], auth()->user()->id);

        return $this->getValidResponse(['message' => 'Cart deleted successfully']);
    }
}
