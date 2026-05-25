<?php

namespace Src\admin\dashboard\application\useCases;

use App\Models\AdminModel;
use App\Models\CustomerModel;
use App\Models\OrderModel;
use App\Models\ProductModel;

class GetDashboardStatsUseCase
{
    public function execute(int $userId): ?array
    {
        $isAdmin = AdminModel::query()
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->exists();

        if (! $isAdmin) {
            return null;
        }

        return [
            'totalCustomers'  => CustomerModel::query()->count(),
            'activeCustomers' => CustomerModel::query()->where('is_active', true)->count(),
            'totalOrders'     => OrderModel::query()->count(),
            'totalProducts'   => ProductModel::query()->count(),
            'activeProducts'  => ProductModel::query()->where('is_active', true)->count(),
            'revenue'         => (float) OrderModel::query()->whereNotIn('order_status', ['cancelled'])->sum('total_amount'),
        ];
    }
}
