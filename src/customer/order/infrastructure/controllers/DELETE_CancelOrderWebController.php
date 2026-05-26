<?php

namespace Src\customer\order\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Src\customer\order\application\useCases\CancelOrderUseCase;
use Src\customer\order\application\useCases\FindOrderUseCase;
use Src\customer\order\domain\exceptions\OrderNotFoundException;
use Src\customer\user\application\useCases\FindCustomerByUserIdUseCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class DELETE_CancelOrderWebController extends Controller
{
    public function __construct(
        private FindCustomerByUserIdUseCase $findCustomer,
        private FindOrderUseCase $findOrder,
        private CancelOrderUseCase $cancelOrder,
    ) {}

    public function destroy(string $orderNumber): RedirectResponse
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

        try {
            $this->cancelOrder->execute($orderNumber);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Pedido cancelado correctamente.');
    }
}
