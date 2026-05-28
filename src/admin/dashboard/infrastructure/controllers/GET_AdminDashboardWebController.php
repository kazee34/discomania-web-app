<?php

namespace Src\admin\dashboard\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Src\admin\dashboard\application\useCases\GetDashboardStatsUseCase;

final class GET_AdminDashboardWebController extends Controller
{
    public function __construct(
        private GetDashboardStatsUseCase $useCase,
    ) {}

    public function index(Request $request): Response
    {
        $stats = $this->useCase->execute($request->user()->id);

        return Inertia::render('Dashboard', [
            'stats' => $stats ? [
                'totalCustomers' => $stats->totalCustomers(),
                'activeCustomers' => $stats->activeCustomers(),
                'totalOrders'    => $stats->totalOrders(),
                'totalProducts'  => $stats->totalProducts(),
                'activeProducts' => $stats->activeProducts(),
                'revenue'        => $stats->revenue(),
            ] : null,
        ]);
    }
}
