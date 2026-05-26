<?php

namespace Src\customer\order\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Src\customer\order\application\useCases\GetCustomerOrdersUseCase;
use Src\customer\user\application\useCases\FindCustomerByUserIdUseCase;

final class GET_ProfileOrdersController extends Controller
{
    public function __construct(
        private FindCustomerByUserIdUseCase $findCustomer,
        private GetCustomerOrdersUseCase $getCustomerOrders,
    ) {}

    public function index(): Response
    {
        $customer = $this->findCustomer->execute(Auth::id());

        $orders = $this->getCustomerOrders->execute($customer->id());

        return Inertia::render('profile/Orders', [
            'orders' => array_map(fn ($o) => [
                'orderNumber' => $o->orderNumber,
                'orderDate' => $o->orderDate,
                'totalAmount' => $o->totalAmount,
                'status' => $o->status,
                'itemCount' => count($o->items),
            ], $orders),
        ]);
    }
}
