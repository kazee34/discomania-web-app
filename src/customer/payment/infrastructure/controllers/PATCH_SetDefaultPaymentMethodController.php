<?php

namespace Src\customer\payment\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Src\customer\payment\application\useCases\SetDefaultPaymentMethodUseCase;
use Src\customer\payment\domain\exceptions\PaymentMethodNotFoundException;
use Src\customer\user\application\useCases\FindCustomerByUserIdUseCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PATCH_SetDefaultPaymentMethodController extends Controller
{
    public function __construct(
        private FindCustomerByUserIdUseCase $findCustomer,
        private SetDefaultPaymentMethodUseCase $setDefault,
    ) {}

    public function update(int $id): RedirectResponse
    {
        $customer = $this->findCustomer->execute(Auth::id());

        try {
            $this->setDefault->execute($id, $customer->id());
        } catch (PaymentMethodNotFoundException) {
            throw new NotFoundHttpException;
        }

        return redirect()->route('customer.payment-methods')
            ->with('success', 'Método de pago predeterminado actualizado.');
    }
}
