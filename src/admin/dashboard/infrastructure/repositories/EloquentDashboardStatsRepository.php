<?php

namespace Src\admin\dashboard\infrastructure\repositories;

use App\Models\AdminModel;
use App\Models\CustomerModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use Src\admin\dashboard\domain\entities\DashboardStats;
use Src\admin\dashboard\domain\repositories\DashboardStatsRepositoryInterface;

class EloquentDashboardStatsRepository implements DashboardStatsRepositoryInterface
{
    public function getStatsForUser(int $userId): ?DashboardStats
    {
        $isAdmin = AdminModel::query()
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->exists();

        if (! $isAdmin) {
            return null;
        }

        return new DashboardStats(
            totalCustomers: (int) CustomerModel::query()->count('*'),
            activeCustomers: (int) CustomerModel::query()->where('is_active', true)->count('*'),
            totalOrders: (int) OrderModel::query()->where('order_status', '!=', 'cancelled')->count('*'),
            totalProducts: (int) ProductModel::query()->count('*'),
            activeProducts: (int) ProductModel::query()->where('is_active', true)->count('*'),
            revenue: (float) OrderModel::query()->where('order_status', '!=', 'cancelled')->sum('total_amount'),
        );
    }
}
