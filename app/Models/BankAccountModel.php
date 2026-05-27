<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankAccountModel extends Model
{
    protected $table = 'bank_accounts';

    protected $fillable = [
        'payment_method_id',
        'account_holder_name',
        'iban_masked',
        'iban_last_four',
        'iban_full',
        'bank_name',
    ];

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethodModel::class, 'payment_method_id');
    }
}
