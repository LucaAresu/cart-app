<?php

namespace App\Services;

interface CartInterface
{
    /**
     * @param string $name
     * @param string|int $userId
     * @return \App\Models\Cart
     */
    public function create(string $name, string|int $userId) : \App\Models\Cart;

    /**
     * @param string|int $id
     * @param string|int $userId
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function delete(string|int $id, string|int $userId) : bool;

}
