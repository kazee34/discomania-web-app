<?php

namespace Src\admin\order\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Src\customer\order\domain\repositories\OrderRepositoryInterface;
use Src\customer\order\domain\valueObjects\OrderStatus;

final class PATCH_AdminOrderStatusWebController extends Controller
{
    public function __construct(
        private OrderRepositoryInterface $repository,
    ) {}

    public function update(string $orderNumber, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:pending,processing,shipped,delivered,cancelled'],
        ]);

        $order = $this->repository->findByOrderNumber($orderNumber);
        $newStatus = OrderStatus::from($data['status']);

        if (! $order->status()->canTransitionTo($newStatus)) {
            return redirect()->back()
                ->with('error', "No se puede pasar de '{$order->status()->value}' a '{$newStatus->value}'.");
        }

        $this->repository->updateStatus($order->id(), $newStatus->value);

        return redirect()->route('admin.orders.show', $orderNumber)
            ->with('success', 'Estado del pedido actualizado.');
    }
}
