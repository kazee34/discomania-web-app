<?php

namespace Src\admin\order\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Src\customer\order\domain\events\OrderCancelledEvent;
use Src\customer\order\domain\events\OrderStatusUpdatedEvent;
use Src\customer\order\domain\repositories\OrderRepositoryInterface;
use Src\customer\order\domain\valueObjects\OrderStatus;
use Src\shared\domain\repositories\EventPublisher;

final class PATCH_AdminOrderStatusWebController extends Controller
{
    public function __construct(
        private OrderRepositoryInterface $repository,
        private EventPublisher $eventPublisher
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

        if ($newStatus === OrderStatus::Cancelled) {
            $this->eventPublisher->publish(new OrderCancelledEvent(
                orderId: (int) $order->id(),
                customerId: $order->customerId(),
                orderNumber: $order->orderNumber(),
            ));
        }

        $this->eventPublisher->publish(new OrderStatusUpdatedEvent(
            customerId: $order->customerId(),
            orderNumber: $order->orderNumber(),
            newStatus: $newStatus,
            totalAmount: $order->totalAmount(),
        ));

        $this->repository->updateStatus($order->id(), $newStatus->value);

        return redirect()->route('admin.orders.show', $orderNumber)
            ->with('success', 'Estado del pedido actualizado.');
    }
}
