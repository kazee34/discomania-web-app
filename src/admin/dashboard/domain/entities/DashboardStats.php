<?php

namespace Src\admin\dashboard\domain\entities;

class DashboardStats
{
    public function __construct(
        private int $totalCustomers,
        private int $activeCustomers,
        private int $totalOrders,
        private int $totalProducts,
        private int $activeProducts,
        private float $revenue
    ) {}

    public function totalCustomers(): int
    {
        return $this->totalCustomers;
    }

    public function activeCustomers(): int
    {
        return $this->activeCustomers;
    }

    public function totalOrders(): int
    {
        return $this->totalOrders;
    }

    public function totalProducts(): int
    {
        return $this->totalProducts;
    }

    public function activeProducts(): int
    {
        return $this->activeProducts;
    }

    public function revenue(): float
    {
        return $this->revenue;
    }

    public static function fromPrimitives(
        int $totalCustomers,
        int $activeCustomers,
        int $totalOrders,
        int $totalProducts,
        int $activeProducts,
        float $revenue
    ): self {
        return new self(
            totalCustomers: $totalCustomers,
            activeCustomers: $activeCustomers,
            totalOrders: $totalOrders,
            totalProducts: $totalProducts,
            activeProducts: $activeProducts,
            revenue: $revenue
        );
    }
}
