<?php

namespace Src\customer\order\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Src\customer\order\application\useCases\FindOrderUseCase;
use Src\customer\order\domain\exceptions\OrderNotFoundException;
use Src\customer\user\application\useCases\FindCustomerByUserIdUseCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class GET_ProfileOrderDetailController extends Controller
{
    public function __construct(
        private FindCustomerByUserIdUseCase $findCustomer,
        private FindOrderUseCase $findOrder,
    ) {}

    public function show(string $orderNumber): Response
    {
        $customer = $this->findCustomer->execute(Auth::id());

        try {
            $order = $this->findOrder->execute($orderNumber);
        } catch (OrderNotFoundException) {
            throw new NotFoundHttpException;
        }

        if ($order->customerId !== $customer->id()) {
            throw new NotFoundHttpException;
        }

        return Inertia::render('profile/OrderDetail', ['order' => $order]);
    }
}
