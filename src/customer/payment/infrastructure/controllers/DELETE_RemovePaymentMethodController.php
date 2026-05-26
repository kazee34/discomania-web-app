<?php

namespace Src\customer\payment\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Src\customer\payment\application\useCases\RemovePaymentMethodUseCase;
use Src\customer\payment\domain\exceptions\PaymentMethodNotFoundException;
use Src\customer\user\application\useCases\FindCustomerByUserIdUseCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class DELETE_RemovePaymentMethodController extends Controller
{
    public function __construct(
        private FindCustomerByUserIdUseCase $findCustomer,
        private RemovePaymentMethodUseCase $removePaymentMethod,
    ) {}

    public function destroy(int $id): RedirectResponse
    {
        $customer = $this->findCustomer->execute(Auth::id());

        try {
            $this->removePaymentMethod->execute($id, $customer->id());
        } catch (PaymentMethodNotFoundException) {
            throw new NotFoundHttpException;
        }

        return redirect()->route('customer.payment-methods')
            ->with('success', 'Método de pago eliminado.');
    }
}
