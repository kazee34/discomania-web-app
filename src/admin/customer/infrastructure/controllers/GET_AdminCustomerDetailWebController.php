<?php

namespace Src\admin\customer\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Src\admin\customer\application\useCases\AdminGetCustomerUseCase;
use Src\customer\order\domain\repositories\OrderRepositoryInterface;

final class GET_AdminCustomerDetailWebController extends Controller
{
    public function __construct(
        private AdminGetCustomerUseCase $useCase,
        private OrderRepositoryInterface $orderRepository,
    ) {}

    public function show(int $id): Response
    {
        $customer = $this->useCase->execute($id);

        $orders = $this->orderRepository->findByCustomerId($id);

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
                'isVip'       => $customer->isVip(),
                'createdAt'   => $customer->createdAt()?->format('Y-m-d'),
                'address'     => $customer->shippingAddress()->toArray(),
            ],
            'orders' => array_map(fn ($o) => [
                'orderNumber' => $o->orderNumber(),
                'orderDate'   => $o->orderDate()->format('Y-m-d'),
                'totalAmount' => $o->totalAmount(),
                'status'      => $o->status()->value,
            ], $orders),
        ]);
    }
}
