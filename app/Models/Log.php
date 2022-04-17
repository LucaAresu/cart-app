<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    const ACTION_CREATE = 'create';
    const ACTION_DELETE = 'delete';
    const ACTION_ADD_PRODUCT = 'add_product';
    const ACTION_REMOVE_PRODUCT = 'remove_product';

    const ACTION_LABELS = [
        self::ACTION_CREATE => 'Created',
        self::ACTION_DELETE => 'Deleted',
        self::ACTION_ADD_PRODUCT => 'Added Product',
        self::ACTION_REMOVE_PRODUCT => 'Removed Product'
    ];

    protected $fillable = ['cart_id', 'user_id', 'product_id', 'action'];

    public function getActionAttribute($value)
    {
        return self::ACTION_LABELS[$value] ?? $value;
    }
}
