<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $hidden = ['pivot', 'id', 'deleted_at'];

    public function carts()
    {
        return $this->belongsToMany(Cart::class)->using(\App\Models\Pivot\CartProduct::class);
    }
}
