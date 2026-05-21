<?php

namespace Src\customer\order\infrastructure\controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Src\customer\order\application\useCases\CreateOrderFromCartUseCase;

final class POST_CheckoutController extends Controller
{
    public function __construct(
        private CreateOrderFromCartUseCase $createOrderFromCartUseCase,
    ) {}

    public function store(Request $request): Response
    {
        $request->validate([
            'cart_token' => ['required', 'string'],
            'customer_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();
        $customer = CustomerModel::where('user_id', $user->id)->firstOrFail();

        $order = $this->createOrderFromCartUseCase->execute(
            cartToken: $request->input('cart_token'),
            customerId: $customer->id,
            customerNotes: $request->input('customer_notes'),
        );

        return Inertia::render('orders/Confirmation', ['order' => $order]);
    }
}