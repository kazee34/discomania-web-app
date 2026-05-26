<?php

namespace Src\customer\payment\domain\valueObjects;

use Src\customer\payment\domain\exceptions\InvalidPaymentDataException;

final class IbanNumber
{
    private const LENGTHS = [
        'AL' => 28, 'AD' => 24, 'AT' => 20, 'AZ' => 28, 'BH' => 22, 'BE' => 16, 'BA' => 20,
        'BR' => 29, 'BG' => 22, 'CR' => 22, 'HR' => 21, 'CY' => 28, 'CZ' => 24, 'DK' => 18,
        'DO' => 28, 'EE' => 20, 'FI' => 18, 'FR' => 27, 'GE' => 22, 'DE' => 22, 'GI' => 23,
        'GR' => 27, 'GT' => 28, 'HU' => 28, 'IS' => 26, 'IE' => 22, 'IL' => 23, 'IT' => 27,
        'JO' => 30, 'KZ' => 20, 'XK' => 20, 'KW' => 30, 'LV' => 21, 'LB' => 28, 'LI' => 21,
        'LT' => 20, 'LU' => 20, 'MT' => 31, 'MR' => 27, 'MU' => 30, 'MC' => 27, 'MD' => 24,
        'ME' => 22, 'NL' => 18, 'MK' => 19, 'NO' => 15, 'PK' => 24, 'PS' => 29, 'PL' => 28,
        'PT' => 25, 'QA' => 29, 'RO' => 24, 'SM' => 27, 'SA' => 24, 'RS' => 22, 'SK' => 24,
        'SI' => 19, 'ES' => 24, 'SE' => 24, 'CH' => 21, 'TN' => 24, 'TR' => 26, 'AE' => 23,
        'GB' => 22, 'VG' => 24,
    ];

    private string $iban;

    public function __construct(string $raw)
    {
        $iban = strtoupper(preg_replace('/\s+/', '', $raw));

        if (! preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]{4,30}$/', $iban)) {
            throw new InvalidPaymentDataException('El formato del IBAN no es válido.');
        }

        $country = substr($iban, 0, 2);
        if (isset(self::LENGTHS[$country]) && strlen($iban) !== self::LENGTHS[$country]) {
            throw new InvalidPaymentDataException("La longitud del IBAN para {$country} debe ser ".self::LENGTHS[$country].' caracteres.');
        }

        if (! self::checkMod97($iban)) {
            throw new InvalidPaymentDataException('El IBAN introducido no es válido (checksum incorrecto).');
        }

        $this->iban = $iban;
    }

    public function value(): string
    {
        return $this->iban;
    }

    public function lastFour(): string
    {
        return substr($this->iban, -4);
    }

    public function masked(): string
    {
        $country = substr($this->iban, 0, 4);
        $last4 = substr($this->iban, -4);
        $middle = str_repeat('*', strlen($this->iban) - 8);
        $formatted = $country.$middle.$last4;

        return implode(' ', str_split($formatted, 4));
    }

    private static function checkMod97(string $iban): bool
    {
        $rearranged = substr($iban, 4).substr($iban, 0, 4);
        $numeric = '';
        foreach (str_split($rearranged) as $char) {
            $numeric .= ctype_alpha($char) ? (string) (ord($char) - 55) : $char;
        }

        $remainder = 0;
        foreach (str_split($numeric, 9) as $chunk) {
            $remainder = (int) ($remainder.$chunk) % 97;
        }

        return $remainder === 1;
    }
}
