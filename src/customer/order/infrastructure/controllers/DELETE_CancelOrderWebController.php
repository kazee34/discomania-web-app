<?php

namespace Src\customer\order\infrastructure\controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomerModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Src\customer\order\application\useCases\CancelOrderUseCase;
use Src\customer\order\application\useCases\FindOrderUseCase;
use Src\customer\order\domain\exceptions\OrderNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class DELETE_CancelOrderWebController extends Controller
{
    public function __construct(
        private FindOrderUseCase $findOrderUseCase,
        private CancelOrderUseCase $cancelOrderUseCase,
    ) {}

    public function destroy(string $orderNumber): RedirectResponse
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

        try {
            $this->cancelOrderUseCase->execute($orderNumber);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Pedido cancelado correctamente.');
    }
}