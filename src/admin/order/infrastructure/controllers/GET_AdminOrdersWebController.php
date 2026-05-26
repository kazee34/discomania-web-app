<?php

namespace Src\admin\order\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Src\admin\customer\domain\repositories\AdminCustomerRepositoryInterface;
use Src\customer\order\domain\repositories\OrderRepositoryInterface;
use Src\shared\domain\repositories\UserRepositoryInterface;

final class GET_AdminOrdersWebController extends Controller
{
    public function __construct(
        private OrderRepositoryInterface $repository,
        private AdminCustomerRepositoryInterface $customerRepository,
        private UserRepositoryInterface $userRepository,
    ) {}

    public function index(): Response
    {
        $orders = $this->repository->findAll();

        return Inertia::render('admin/orders/Index', [
            'orders' => array_map(fn ($o) => [
                'id'          => $o->id(),
                'orderNumber' => $o->orderNumber(),
                'customerId'  => $o->customerId(),
                'orderDate'   => $o->orderDate()->format('Y-m-d'),
                'totalAmount' => $o->totalAmount(),
                'status'      => $o->status()->value,
                'itemCount'   => count($o->items()),
            ], $orders),
        ]);
    }

    public function show(string $orderNumber): Response
    {
        $order    = $this->repository->findByOrderNumber($orderNumber);
        $customer = $this->customerRepository->findById($order->customerId());
        $user     = $customer ? $this->userRepository->findById($customer->userId()) : null;

        return Inertia::render('admin/orders/Show', [
            'order' => [
                'id'            => $order->id(),
                'orderNumber'   => $order->orderNumber(),
                'customerId'    => $order->customerId(),
                'orderDate'     => $order->orderDate()->format('Y-m-d H:i'),
                'subtotal'      => $order->subtotal(),
                'shippingCost'  => $order->shippingCost(),
                'taxAmount'     => $order->taxAmount(),
                'totalAmount'   => $order->totalAmount(),
                'status'        => $order->status()->value,
                'trackingNumber'=> $order->trackingNumber(),
                'customerNotes' => $order->customerNotes(),
                'adminNotes'    => $order->adminNotes(),
                'items'         => array_map(fn ($i) => [
                    'id'              => $i->id(),
                    'productSnapshot' => $i->productSnapshot(),
                    'quantity'        => $i->quantity(),
                    'pricePerUnit'    => $i->pricePerUnit(),
                    'subtotal'        => $i->subtotal(),
                ], $order->items()),
                'customer' => $customer ? [
                    'id'    => $customer->id(),
                    'name'  => $customer->firstName()->value() . ' ' . $customer->lastName()->value(),
                    'phone' => $customer->phone()->value(),
                    'email' => $user?->email()->value(),
                ] : null,
            ],
        ]);
    }
}
