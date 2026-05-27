<?php

namespace Src\admin\dashboard\application\useCases;

use Src\admin\dashboard\domain\entities\DashboardStats;
use Src\admin\dashboard\domain\repositories\DashboardStatsRepositoryInterface;

class GetDashboardStatsUseCase
{
    public function __construct(
        private DashboardStatsRepositoryInterface $dashboardStatsRepository
    ) {}

    public function execute(int $userId): ?DashboardStats
    {
        return $this->dashboardStatsRepository->getStatsForUser($userId);
    }
}
