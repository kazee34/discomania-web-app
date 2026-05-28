<?php

namespace Src\customer\payment\infrastructure\repositories;

use App\Models\OrderPaymentModel;
use Src\customer\payment\domain\entities\OrderPayment;
use Src\customer\payment\domain\repositories\OrderPaymentRepositoryInterface;

final class EloquentOrderPaymentRepository implements OrderPaymentRepositoryInterface
{
    public function save(OrderPayment $payment): int
    {
        if ($payment->id() !== null) {
            OrderPaymentModel::where('id', $payment->id())->update([
                'status' => $payment->status()->value,
            ]);

            return $payment->id();
        }

        $model = OrderPaymentModel::create([
            'order_id' => $payment->orderId(),
            'payment_method_id' => $payment->paymentMethodId(),
            'payment_type' => $payment->paymentType()->value,
            'payment_summary' => $payment->paymentSummary(),
            'status' => $payment->status()->value,
            'mock_transaction_id' => $payment->mockTransactionId(),
            'amount' => $payment->amount(),
            'currency' => $payment->currency(),
            'processed_at' => $payment->processedAt(),
        ]);

        $payment->setId($model->id);

        return $model->id;
    }

    public function findByOrderId(int $orderId): ?OrderPayment
    {
        $model = OrderPaymentModel::where('order_id', $orderId)->first();

        if ($model === null) {
            return null;
        }

        return OrderPayment::fromPrimitives(
            id: $model->id,
            orderId: $model->order_id,
            paymentMethodId: $model->payment_method_id,
            paymentType: $model->payment_type,
            paymentSummary: $model->payment_summary,
            status: $model->status,
            mockTransactionId: $model->mock_transaction_id,
            amount: (float) $model->amount,
            currency: $model->currency,
            processedAt: $model->processed_at->toIso8601String(),
        );
    }
}
