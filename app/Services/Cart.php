<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class Cart implements CartInterface
{


    /**
     * @inheritdoc
     */
    public function create(string $name): \App\Models\Cart
    {
        Validator::validate(
            compact('name'),
            [
                'name' => ['required']
            ]
        );

        return \App\Models\Cart::create([
            'name' => $name
        ]);
    }

    /**
     * @inheritdoc
     */
    public function delete(int|string $id): bool
    {
        return \App\Models\Cart::findOrFail($id)->delete();
    }
}
