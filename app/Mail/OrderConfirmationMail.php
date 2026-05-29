<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Src\customer\order\application\dto\OrderResult;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $customerName,
        public readonly OrderResult $order,
        public readonly array $shippingAddress,
        public readonly string $paymentSummary,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Confirmación de pedido {$this->order->orderNumber}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation',
        );
    }
}
