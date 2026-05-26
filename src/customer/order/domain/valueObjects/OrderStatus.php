<?php

namespace Src\customer\order\domain\valueObjects;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

    public function canTransitionTo(self $next): bool
    {
        return match ($this) {
            self::Pending => $next === self::Processing || $next === self::Cancelled,
            self::Processing => $next === self::Shipped || $next === self::Cancelled,
            self::Shipped => $next === self::Delivered,
            default => false,
        };
    }

    public function isPending(): bool
    {
        return $this === self::Pending;
    }

    public function isProcessing(): bool
    {
        return $this === self::Processing;
    }

    public function isShipped(): bool
    {
        return $this === self::Shipped;
    }

    public function isDelivered(): bool
    {
        return $this === self::Delivered;
    }

    public function isCancelled(): bool
    {
        return $this === self::Cancelled;
    }
}
