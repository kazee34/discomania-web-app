<?php

namespace Src\customer\order\infrastructure\controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomerModel;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Src\customer\order\application\useCases\GetCustomerOrdersUseCase;

final class GET_ProfileOrdersController extends Controller
{
    public function __construct(
        private GetCustomerOrdersUseCase $getCustomerOrdersUseCase,
    ) {}

    public function index(): Response
    {
        $user = Auth::user();
        $customer = CustomerModel::where('user_id', $user->id)->firstOrFail();

        $orders = $this->getCustomerOrdersUseCase->execute($customer->id);

        return Inertia::render('profile/Orders', [
            'orders' => array_map(fn ($o) => [
                'orderNumber' => $o->orderNumber,
                'orderDate'   => $o->orderDate,
                'totalAmount' => $o->totalAmount,
                'status'      => $o->status,
                'itemCount'   => count($o->items),
            ], $orders),
        ]);
    }
}