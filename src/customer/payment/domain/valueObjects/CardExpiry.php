<?php

namespace Src\customer\payment\domain\valueObjects;

use Src\customer\payment\domain\exceptions\InvalidPaymentDataException;

final class CardExpiry
{
    public function __construct(
        private int $month,
        private int $year,
    ) {
        if ($month < 1 || $month > 12) {
            throw new InvalidPaymentDataException('El mes de caducidad no es válido.');
        }

        $currentYear = (int) date('Y');
        $currentMonth = (int) date('n');

        if ($year < $currentYear || ($year === $currentYear && $month < $currentMonth)) {
            throw new InvalidPaymentDataException('La tarjeta está caducada.');
        }
    }

    public function month(): int
    {
        return $this->month;
    }

    public function year(): int
    {
        return $this->year;
    }
}
