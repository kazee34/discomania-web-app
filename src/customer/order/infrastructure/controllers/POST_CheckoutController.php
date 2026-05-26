<?php

namespace Src\customer\order\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Src\customer\order\application\useCases\CreateOrderFromCartUseCase;
use Src\customer\user\application\useCases\FindCustomerByUserIdUseCase;

final class POST_CheckoutController extends Controller
{
    public function __construct(
        private FindCustomerByUserIdUseCase $findCustomer,
        private CreateOrderFromCartUseCase $createOrderFromCart,
    ) {}

    public function store(Request $request): Response
    {
        $request->validate([
            'cart_token'     => ['required', 'string'],
            'customer_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $customer = $this->findCustomer->execute(Auth::id());

        $order = $this->createOrderFromCart->execute(
            cartToken: $request->input('cart_token'),
            customerId: $customer->id(),
            shippingAddress: $customer->shippingAddress(),
            customerNotes: $request->input('customer_notes'),
        );

        return Inertia::render('orders/Confirmation', ['order' => $order]);
    }
}
