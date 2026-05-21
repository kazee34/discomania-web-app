<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderModel extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'customer_id',
        'order_date',
        'subtotal',
        'shipping_cost',
        'tax_amount',
        'total_amount',
        'order_status',
        'tracking_number',
        'estimated_delivery_date',
        'customer_notes',
        'admin_notes',
    ];

    protected $casts = [
        'order_date'              => 'immutable_datetime',
        'subtotal'                => 'decimal:2',
        'shipping_cost'           => 'decimal:2',
        'tax_amount'              => 'decimal:2',
        'total_amount'            => 'decimal:2',
        'estimated_delivery_date' => 'immutable_date',
        'created_at'              => 'immutable_datetime',
        'updated_at'              => 'immutable_datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItemModel::class, 'order_id');
    }
}