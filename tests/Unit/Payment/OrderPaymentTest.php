<?php

namespace Tests\Unit\Payment;

use PHPUnit\Framework\TestCase;
use Src\customer\payment\domain\entities\OrderPayment;
use Src\customer\payment\domain\valueObjects\PaymentMethodType;
use Src\customer\payment\domain\valueObjects\PaymentStatus;

class OrderPaymentTest extends TestCase
{
    private function makePayment(): OrderPayment
    {
        return OrderPayment::create(
            orderId: 1,
            paymentMethodId: null,
            paymentType: PaymentMethodType::CreditCard,
            paymentSummary: 'Visa **** 1234',
            amount: 99.99,
            mockTransactionId: 'txn_test_001',
        );
    }

    public function test_create_has_completed_status_by_default(): void
    {
        $payment = $this->makePayment();

        $this->assertTrue($payment->status()->isCompleted());
        $this->assertFalse($payment->status()->isRefunded());
    }

    public function test_refund_changes_status_to_refunded(): void
    {
        $payment = $this->makePayment();
        $payment->refund();

        $this->assertTrue($payment->status()->isRefunded());
        $this->assertFalse($payment->status()->isCompleted());
        $this->assertEquals(PaymentStatus::Refunded, $payment->status());
    }

    public function test_refund_status_serializes_to_string(): void
    {
        $payment = $this->makePayment();
        $payment->refund();

        $this->assertEquals('refunded', $payment->status()->value);
    }
}
