<?php

namespace Src\customer\order\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Src\customer\order\application\useCases\CreateOrderFromCartUseCase;
use Src\customer\payment\application\useCases\ProcessPaymentUseCase;
use Src\customer\payment\domain\exceptions\InvalidPaymentDataException;
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
        $currentYear  = (int) date('Y');
        $currentMonth = (int) date('n');

        $request->validate([
            'cart_token'        => ['required', 'string'],
            'customer_notes'    => ['nullable', 'string', 'max:500'],
            'payment_method_id' => ['nullable', 'integer', 'exists:payment_methods,id'],
            'payment_type'      => ['required_without:payment_method_id', 'nullable', 'in:credit_card,sepa_debit'],
            'card_number'       => ['required_if:payment_type,credit_card', 'nullable', 'string'],
            'card_brand'        => ['required_if:payment_type,credit_card', 'nullable', 'in:visa,mastercard'],
            'card_holder'       => ['required_if:payment_type,credit_card', 'nullable', 'string', 'max:100'],
            'expiry_month'      => [
                'required_if:payment_type,credit_card', 'nullable', 'integer', 'min:1', 'max:12',
                function ($_attribute, $value, $fail) use ($currentYear, $currentMonth, $request) {
                    $year = (int) $request->input('expiry_year');
                    if ($year === $currentYear && (int) $value < $currentMonth) {
                        $fail('La tarjeta está caducada.');
                    }
                },
            ],
            'expiry_year'       => ['required_if:payment_type,credit_card', 'nullable', 'integer', 'min:'.$currentYear],
            'cvv'               => ['required_if:payment_type,credit_card', 'nullable', 'string', 'regex:/^\d{3}$/'],
            'iban'              => ['required_if:payment_type,sepa_debit', 'nullable', 'string', 'regex:/^ES\d{22}$/i'],
            'account_holder'    => ['required_if:payment_type,sepa_debit', 'nullable', 'string', 'max:100'],
            'bank_name'         => ['nullable', 'string', 'max:100'],
            'set_as_default'    => ['boolean'],
        ], [
            'cart_token.required'           => 'El token del carrito es obligatorio.',
            'payment_method_id.exists'      => 'El método de pago seleccionado no existe.',
            'payment_type.required_without' => 'Debes seleccionar un método de pago.',
            'payment_type.in'               => 'El tipo de pago no es válido.',
            'card_number.required_if'       => 'El número de tarjeta es obligatorio.',
            'card_brand.required_if'        => 'El tipo de tarjeta es obligatorio.',
            'card_brand.in'                 => 'Solo se aceptan tarjetas Visa y Mastercard.',
            'card_holder.required_if'       => 'El nombre del titular es obligatorio.',
            'expiry_month.required_if'      => 'El mes de caducidad es obligatorio.',
            'expiry_month.min'              => 'El mes de caducidad no es válido.',
            'expiry_month.max'              => 'El mes de caducidad no es válido.',
            'expiry_year.required_if'       => 'El año de caducidad es obligatorio.',
            'expiry_year.min'               => 'La tarjeta está caducada.',
            'cvv.required_if'               => 'El CVV es obligatorio.',
            'cvv.regex'                     => 'El CVV debe tener 3 dígitos.',
            'iban.required_if'              => 'El IBAN es obligatorio.',
            'account_holder.required_if'    => 'El nombre del titular de la cuenta es obligatorio.',
        ]);

        $customer = $this->findCustomer->execute(Auth::id());

        $paymentMethodId = $request->input('payment_method_id') !== null
            ? (int) $request->input('payment_method_id')
            : null;

        $inlineData = null;
        if ($paymentMethodId === null) {
            $type = $request->input('payment_type');
            $setAsDefault = (bool) $request->input('set_as_default', false);
            $inlineData = ['type' => $type, 'set_as_default' => $setAsDefault];
            if ($type === 'credit_card') {
                $inlineData += [
                    'card_number'  => $request->input('card_number'),
                    'card_brand'   => $request->input('card_brand'),
                    'card_holder'  => $request->input('card_holder'),
                    'expiry_month' => (int) $request->input('expiry_month'),
                    'expiry_year'  => (int) $request->input('expiry_year'),
                ];
            } else {
                $inlineData += [
                    'iban'           => $request->input('iban'),
                    'account_holder' => $request->input('account_holder'),
                    'bank_name'      => $request->input('bank_name'),
                ];
            }
        }

        try {
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
        } catch (InvalidPaymentDataException $e) {
            $field = ($inlineData['type'] ?? '') === 'credit_card' ? 'card_number' : 'iban';
            throw ValidationException::withMessages([$field => $e->getMessage()]);
        }

        return Inertia::render('orders/Confirmation', [
            'order'   => $order,
            'payment' => $payment,
        ]);
    }
}
