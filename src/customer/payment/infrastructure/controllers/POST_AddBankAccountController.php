<?php

namespace Src\customer\payment\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Src\customer\payment\application\useCases\AddBankAccountUseCase;
use Src\customer\payment\domain\exceptions\InvalidPaymentDataException;
use Src\customer\payment\infrastructure\validators\AddBankAccountRequest;
use Src\customer\user\application\useCases\FindCustomerByUserIdUseCase;

final class POST_AddBankAccountController extends Controller
{
    public function __construct(
        private FindCustomerByUserIdUseCase $findCustomer,
        private AddBankAccountUseCase $addBankAccount,
    ) {}

    public function store(AddBankAccountRequest $request): RedirectResponse
    {
        $customer = $this->findCustomer->execute(Auth::id());

        try {
            $this->addBankAccount->execute(
                customerId: $customer->id(),
                ibanRaw: $request->input('iban'),
                accountHolderName: $request->input('account_holder'),
                bankName: $request->input('bank_name'),
                setAsDefault: (bool) $request->input('set_as_default', false),
            );
        } catch (InvalidPaymentDataException $e) {
            return back()->withErrors(['iban' => $e->getMessage()]);
        }

        return redirect()->route('customer.payment-methods')
            ->with('success', 'Cuenta bancaria añadida correctamente.');
    }
}
