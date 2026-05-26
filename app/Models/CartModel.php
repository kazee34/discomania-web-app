<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CartModel extends Model
{
    protected $table = 'carts';

    protected $fillable = [
        'customer_id',
        'session_id',
        'cart_token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'immutable_datetime',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(CartItemModel::class, 'cart_id');
    }
}
