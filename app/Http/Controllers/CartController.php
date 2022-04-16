<?php

namespace App\Http\Controllers;

use App\Services\CartInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CartController extends AbstractApiController
{

    public function __construct(protected CartInterface $cart) {}

    public function create()
    {
        $validator = Validator::make(
            request()->all(),
            [
                'name' => ['required']
            ]
        );
        if ($validator->fails()) {
            return $this->getValidationErrorResponse($validator->messages());
        }

        $cart = $this->cart->create(request()->get('name'));
        return $this->getValidResponse(['id' => $cart->id]);
    }
}
