<?php

namespace Src\customer\payment\domain\valueObjects;

use Src\customer\payment\domain\exceptions\InvalidPaymentDataException;

final class IbanNumber
{
    private string $iban;

    public function __construct(string $raw)
    {
        $iban = strtoupper(preg_replace('/\s+/', '', $raw));

        if (! preg_match('/^ES\d{22}$/', $iban)) {
            throw new InvalidPaymentDataException('Solo se aceptan IBANs españoles (ES + 22 dígitos).');
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
        $lastFour = $this->lastFour();
        $middle = str_repeat('*', strlen($this->iban) - 8);
        $formatted = $country.$middle.$lastFour;

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
