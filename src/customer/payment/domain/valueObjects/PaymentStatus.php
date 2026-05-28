<?php

namespace Src\customer\payment\domain\valueObjects;

enum PaymentStatus: string
{
    case Completed = 'completed';
    case Failed = 'failed';
    case Refunded = 'refunded';

    public function isCompleted(): bool
    {
        return $this === self::Completed;
    }

    public function isFailed(): bool
    {
        return $this === self::Failed;
    }

    public function isRefunded(): bool
    {
        return $this === self::Refunded;
    }
}
