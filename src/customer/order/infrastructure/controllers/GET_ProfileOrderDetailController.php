<?php

namespace Src\customer\order\infrastructure\controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomerModel;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Src\customer\order\application\useCases\FindOrderUseCase;
use Src\customer\order\domain\exceptions\OrderNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class GET_ProfileOrderDetailController extends Controller
{
    public function __construct(
        private FindOrderUseCase $findOrderUseCase,
    ) {}

    public function show(string $orderNumber): Response
    {
        $user = Auth::user();
        $customer = CustomerModel::where('user_id', $user->id)->firstOrFail();

        try {
            $order = $this->findOrderUseCase->execute($orderNumber);
        } catch (OrderNotFoundException) {
            throw new NotFoundHttpException();
        }

        if ($order->customerId !== $customer->id) {
            throw new NotFoundHttpException();
        }

        return Inertia::render('profile/OrderDetail', ['order' => $order]);
    }
}