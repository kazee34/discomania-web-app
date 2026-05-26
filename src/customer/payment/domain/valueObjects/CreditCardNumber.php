<?php

namespace Src\customer\payment\domain\valueObjects;

use Src\customer\payment\domain\exceptions\InvalidPaymentDataException;

final class CreditCardNumber
{
    private string $digits;

    private CardBrand $brand;

    public function __construct(string $raw, CardBrand $expectedBrand)
    {
        $digits = preg_replace('/\s+/', '', $raw);

        if (! ctype_digit($digits) || strlen($digits) !== 16) {
            throw new InvalidPaymentDataException('El número de tarjeta debe tener 16 dígitos.');
        }

        if (! self::luhn($digits)) {
            throw new InvalidPaymentDataException('El número de tarjeta no es válido.');
        }

        $detectedBrand = self::detectBrand($digits);
        if ($detectedBrand !== $expectedBrand) {
            throw new InvalidPaymentDataException(
                "El número introducido no corresponde a {$expectedBrand->label()}."
            );
        }

        $this->digits = $digits;
        $this->brand = $detectedBrand;
    }

    public function lastFour(): string
    {
        return substr($this->digits, -4);
    }

    public function brand(): CardBrand
    {
        return $this->brand;
    }

    private static function luhn(string $number): bool
    {
        $sum = 0;
        $flip = false;
        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            $digit = (int) $number[$i];
            if ($flip) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
            $flip = ! $flip;
        }

        return $sum % 10 === 0;
    }

    private static function detectBrand(string $digits): CardBrand
    {
        if ($digits[0] === '4') {
            return CardBrand::Visa;
        }

        $prefix2 = (int) substr($digits, 0, 2);
        $prefix4 = (int) substr($digits, 0, 4);
        $prefix6 = (int) substr($digits, 0, 6);

        $isMastercardClassic = $prefix2 >= 51 && $prefix2 <= 55;
        $isMastercardNew = $prefix6 >= 222100 && $prefix6 <= 272099;

        if ($isMastercardClassic || $isMastercardNew) {
            return CardBrand::Mastercard;
        }

        throw new InvalidPaymentDataException('Solo se aceptan tarjetas Visa y Mastercard.');
    }
}
