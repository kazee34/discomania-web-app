<?php

namespace Src\admin\customer\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Src\admin\customer\application\useCases\AdminListCustomersUseCase;

final class GET_AdminCustomersWebController extends Controller
{
    public function __construct(
        private AdminListCustomersUseCase $useCase,
    ) {}

    public function index(): Response
    {
        $customers = $this->useCase->execute();

        return Inertia::render('admin/customers/Index', [
            'customers' => array_map(fn ($c) => [
                'id' => $c->id(),
                'firstName' => $c->firstName()->value(),
                'lastName' => $c->lastName()->value(),
                'phone' => $c->phone()->value(),
                'dniNif' => $c->dniNif()->value(),
                'totalOrders' => $c->totalOrders(),
                'isActive' => $c->isActive(),
                'isVip' => $c->isVip(),
                'createdAt' => $c->createdAt()?->format('Y-m-d'),
            ], $customers),
        ]);
    }
}
