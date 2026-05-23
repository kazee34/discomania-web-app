<?php

namespace Src\admin\customer\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Src\admin\customer\application\useCases\AdminGetCustomerUseCase;

final class GET_AdminCustomerDetailWebController extends Controller
{
    public function __construct(
        private AdminGetCustomerUseCase $useCase,
    ) {}

    public function show(int $id): Response
    {
        $customer = $this->useCase->execute($id);

        return Inertia::render('admin/customers/Show', [
            'customer' => [
                'id'          => $customer->id(),
                'firstName'   => $customer->firstName()->value(),
                'lastName'    => $customer->lastName()->value(),
                'phone'       => $customer->phone()->value(),
                'dniNif'      => $customer->dniNif()->value(),
                'birthDate'   => $customer->birthDate(),
                'totalOrders' => $customer->totalOrders(),
                'isActive'    => $customer->isActive(),
                'createdAt'   => $customer->createdAt()?->format('Y-m-d'),
                'address'     => $customer->shippingAddress()->toArray(),
            ],
        ]);
    }
}
