<?php

namespace Src\customer\order\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Src\customer\order\application\useCases\CreateOrderFromCartUseCase;
use Src\customer\payment\application\useCases\ProcessPaymentUseCase;
use Src\customer\user\application\useCases\FindCustomerByUserIdUseCase;

final class POST_CheckoutController extends Controller
{
    public function __construct(
        private FindCustomerByUserIdUseCase $findCustomer,
        private CreateOrderFromCartUseCase $createOrderFromCart,
        private ProcessPaymentUseCase $processPayment,
    ) {}

    public function store(Request $request): Response
    {
        $request->validate([
            'cart_token' => ['required', 'string'],
            'customer_notes' => ['nullable', 'string', 'max:500'],
            // Método guardado:
            'payment_method_id' => ['nullable', 'integer', 'exists:payment_methods,id'],
            // Datos inline (cuando no hay método guardado):
            'payment_type' => ['required_without:payment_method_id', 'nullable', 'in:credit_card,sepa_debit'],
            'card_number' => ['required_if:payment_type,credit_card', 'nullable', 'string'],
            'card_brand' => ['required_if:payment_type,credit_card', 'nullable', 'in:visa,mastercard'],
            'card_holder' => ['required_if:payment_type,credit_card', 'nullable', 'string', 'max:100'],
            'expiry_month' => ['required_if:payment_type,credit_card', 'nullable', 'integer', 'min:1', 'max:12'],
            'expiry_year' => ['required_if:payment_type,credit_card', 'nullable', 'integer', 'min:'.date('Y')],
            'cvv' => ['required_if:payment_type,credit_card', 'nullable', 'string', 'regex:/^\d{3}$/'],
            'iban' => ['required_if:payment_type,sepa_debit', 'nullable', 'string'],
            'account_holder' => ['required_if:payment_type,sepa_debit', 'nullable', 'string', 'max:100'],
            'bic' => ['nullable', 'string'],
            'bank_name' => ['nullable', 'string', 'max:100'],
        ]);

        $customer = $this->findCustomer->execute(Auth::id());

        $paymentMethodId = $request->input('payment_method_id') !== null
            ? (int) $request->input('payment_method_id')
            : null;

        $inlineData = null;
        if ($paymentMethodId === null) {
            $type = $request->input('payment_type');
            $inlineData = ['type' => $type];
            if ($type === 'credit_card') {
                $inlineData += [
                    'card_number' => $request->input('card_number'),
                    'card_brand' => $request->input('card_brand'),
                    'card_holder' => $request->input('card_holder'),
                    'expiry_month' => (int) $request->input('expiry_month'),
                    'expiry_year' => (int) $request->input('expiry_year'),
                ];
            } else {
                $inlineData += [
                    'iban' => $request->input('iban'),
                    'account_holder' => $request->input('account_holder'),
                    'bic' => $request->input('bic'),
                    'bank_name' => $request->input('bank_name'),
                ];
            }
        }

        [$order, $payment] = DB::transaction(function () use ($request, $customer, $paymentMethodId, $inlineData) {
            $order = $this->createOrderFromCart->execute(
                cartToken: $request->input('cart_token'),
                customerId: $customer->id(),
                shippingAddress: $customer->shippingAddress(),
                customerNotes: $request->input('customer_notes'),
            );

            $payment = $this->processPayment->execute(
                orderId: $order->id,
                customerId: $customer->id(),
                amount: $order->totalAmount,
                paymentMethodId: $paymentMethodId,
                inlineData: $inlineData,
            );

            return [$order, $payment];
        });

        return Inertia::render('orders/Confirmation', [
            'order' => $order,
            'payment' => $payment,
        ]);
    }
}
