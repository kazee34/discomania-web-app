<?php

namespace Src\customer\payment\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Src\customer\payment\application\useCases\AddCreditCardUseCase;
use Src\customer\payment\domain\exceptions\InvalidPaymentDataException;
use Src\customer\payment\infrastructure\validators\AddCreditCardRequest;
use Src\customer\user\application\useCases\FindCustomerByUserIdUseCase;

final class POST_AddCreditCardController extends Controller
{
    public function __construct(
        private FindCustomerByUserIdUseCase $findCustomer,
        private AddCreditCardUseCase $addCreditCard,
    ) {}

    public function store(AddCreditCardRequest $request): RedirectResponse
    {
        $customer = $this->findCustomer->execute(Auth::id());

        try {
            $this->addCreditCard->execute(
                customerId: $customer->id(),
                cardNumber: $request->input('card_number'),
                cardBrand: $request->input('card_brand'),
                cardHolderName: $request->input('card_holder'),
                expiryMonth: (int) $request->input('expiry_month'),
                expiryYear: (int) $request->input('expiry_year'),
                setAsDefault: (bool) $request->input('set_as_default', false),
            );
        } catch (InvalidPaymentDataException $e) {
            return back()->withErrors(['card_number' => $e->getMessage()]);
        }

        return redirect()->route('customer.payment-methods')
            ->with('success', 'Tarjeta añadida correctamente.');
    }
}
