<?php

namespace Src\admin\dashboard\domain\repositories;

use Src\admin\dashboard\domain\entities\DashboardStats;

interface DashboardStatsRepositoryInterface
{
    public function getStatsForUser(int $userId): ?DashboardStats;
}
