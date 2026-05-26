<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PaymentMethodModel extends Model
{
    protected $table = 'payment_methods';

    protected $fillable = [
        'customer_id',
        'type',
        'is_default',
        'label',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerModel::class, 'customer_id');
    }

    public function creditCard(): HasOne
    {
        return $this->hasOne(CreditCardModel::class, 'payment_method_id');
    }

    public function bankAccount(): HasOne
    {
        return $this->hasOne(BankAccountModel::class, 'payment_method_id');
    }

    public function orderPayment(): HasOne
    {
        return $this->hasOne(OrderPaymentModel::class, 'payment_method_id');
    }
}
