<?php

namespace Src\customer\payment\domain\valueObjects;

enum CardBrand: string
{
    case Visa = 'visa';
    case Mastercard = 'mastercard';

    public function label(): string
    {
        return match ($this) {
            self::Visa => 'Visa',
            self::Mastercard => 'Mastercard',
        };
    }
}
