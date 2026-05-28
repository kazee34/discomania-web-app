<?php

namespace Src\customer\order\infrastructure\repositories;

use App\Models\OrderItemModel;
use App\Models\OrderModel;
use Illuminate\Support\Facades\DB;
use Src\customer\order\domain\entities\Order;
use Src\customer\order\domain\entities\OrderItem;
use Src\customer\order\domain\exceptions\OrderNotFoundException;
use Src\customer\order\domain\repositories\OrderRepositoryInterface;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function save(Order $order): int
    {
        return DB::transaction(function () use ($order) {
            $model = OrderModel::create([
                'order_number' => $order->orderNumber(),
                'customer_id' => $order->customerId(),
                'order_date' => $order->orderDate(),
                'subtotal' => $order->subtotal(),
                'shipping_cost' => $order->shippingCost(),
                'tax_amount' => $order->taxAmount(),
                'discount_amount' => $order->discountAmount(),
                'total_amount' => $order->totalAmount(),
                'order_status' => $order->status()->value,
                'tracking_number' => $order->trackingNumber(),
                'estimated_delivery_date' => $order->estimatedDeliveryDate(),
                'customer_notes' => $order->customerNotes(),
                'admin_notes' => $order->adminNotes(),
            ]);

            foreach ($order->items() as $item) {
                OrderItemModel::create([
                    'order_id' => $model->id,
                    'product_id' => $item->productId(),
                    'product_snapshot' => $item->productSnapshot(),
                    'quantity' => $item->quantity(),
                    'price_per_unit' => $item->pricePerUnit(),
                    'subtotal' => $item->subtotal(),
                ]);
            }

            return $model->id;
        });
    }

    public function findById(int $id): Order
    {
        $model = OrderModel::with('items')->find($id);

        if (! $model) {
            throw OrderNotFoundException::byId($id);
        }

        return $this->toOrder($model);
    }

    public function findByOrderNumber(string $orderNumber): Order
    {
        $model = OrderModel::with('items')->where('order_number', $orderNumber)->first();

        if (! $model) {
            throw OrderNotFoundException::byOrderNumber($orderNumber);
        }

        return $this->toOrder($model);
    }

    public function findAll(): array
    {
        return OrderModel::with('items')
            ->orderByDesc('order_date')
            ->get()
            ->map(fn (OrderModel $model) => $this->toOrder($model))
            ->all();
    }

    public function findByCustomerId(int $customerId): array
    {
        return OrderModel::with('items')
            ->where('customer_id', $customerId)
            ->orderByDesc('order_date')
            ->get()
            ->map(fn (OrderModel $model) => $this->toOrder($model))
            ->all();
    }

    public function updateStatus(int $orderId, string $status): void
    {
        OrderModel::whereKey($orderId)->update(['order_status' => $status]);
    }

    private function toOrder(OrderModel $model): Order
    {
        $items = $model->items->map(fn (OrderItemModel $item) => OrderItem::fromPrimitives(
            id: $item->id,
            orderId: $item->order_id,
            productId: $item->product_id,
            productSnapshot: $item->product_snapshot,
            quantity: $item->quantity,
            pricePerUnit: (float) $item->price_per_unit,
        ))->all();

        return Order::fromPrimitives(
            id: $model->id,
            orderNumber: $model->order_number,
            customerId: $model->customer_id,
            orderDate: $model->order_date,
            subtotal: (float) $model->subtotal,
            shippingCost: (float) $model->shipping_cost,
            taxAmount: (float) $model->tax_amount,
            discountAmount: (float) $model->discount_amount,
            totalAmount: (float) $model->total_amount,
            status: $model->order_status,
            trackingNumber: $model->tracking_number,
            estimatedDeliveryDate: $model->estimated_delivery_date,
            customerNotes: $model->customer_notes,
            adminNotes: $model->admin_notes,
            items: $items,
        );
    }
}
