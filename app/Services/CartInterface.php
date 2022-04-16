<?php

namespace App\Services;

interface CartInterface
{
    /**
     * @param string $name
     * @return \App\Models\Cart
     */
    public function create(string $name) : \App\Models\Cart;

    /**
     * @param string|int $id
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function delete(string|int $id) : bool;

}
