<?php

namespace Src\customer\payment\domain\valueObjects;

enum PaymentMethodType: string
{
    case CreditCard = 'credit_card';
    case SepaDebit = 'sepa_debit';
}
