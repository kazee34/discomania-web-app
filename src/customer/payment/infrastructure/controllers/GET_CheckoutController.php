<?php

namespace Src\customer\payment\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Src\customer\payment\application\useCases\ListPaymentMethodsUseCase;
use Src\customer\user\application\useCases\FindCustomerByUserIdUseCase;

final class GET_CheckoutController extends Controller
{
    public function __construct(
        private FindCustomerByUserIdUseCase $findCustomer,
        private ListPaymentMethodsUseCase $listPaymentMethods,
    ) {}

    public function show(Request $request): Response
    {
        $customer = $this->findCustomer->execute(Auth::id());
        $methods = $this->listPaymentMethods->execute($customer->id());

        return Inertia::render('checkout/Index', [
            'cartToken' => $request->query('cart_token', ''),
            'savedMethods' => $methods,
        ]);
    }
}
