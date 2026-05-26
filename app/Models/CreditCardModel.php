<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditCardModel extends Model
{
    protected $table = 'credit_cards';

    protected $fillable = [
        'payment_method_id',
        'card_holder_name',
        'card_brand',
        'card_last_four',
        'card_expiry_month',
        'card_expiry_year',
    ];

    protected $casts = [
        'card_expiry_month' => 'integer',
        'card_expiry_year' => 'integer',
    ];

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethodModel::class, 'payment_method_id');
    }
}
