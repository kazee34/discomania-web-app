<?php

namespace Src\customer\payment\infrastructure\repositories;

use App\Models\BankAccountModel;
use App\Models\CreditCardModel;
use App\Models\PaymentMethodModel;
use Illuminate\Support\Facades\DB;
use Src\customer\payment\domain\entities\BankAccount;
use Src\customer\payment\domain\entities\CreditCard;
use Src\customer\payment\domain\entities\PaymentMethod;
use Src\customer\payment\domain\exceptions\PaymentMethodNotFoundException;
use Src\customer\payment\domain\repositories\PaymentMethodRepositoryInterface;

final class EloquentPaymentMethodRepository implements PaymentMethodRepositoryInterface
{
    public function save(PaymentMethod $method): int
    {
        return DB::transaction(function () use ($method) {
            $model = PaymentMethodModel::create([
                'customer_id' => $method->customerId(),
                'type' => $method->type()->value,
                'is_default' => $method->isDefault(),
                'label' => $method->label(),
            ]);

            if ($method->creditCard() !== null) {
                $card = $method->creditCard();
                CreditCardModel::create([
                    'payment_method_id' => $model->id,
                    'card_holder_name' => $card->cardHolderName(),
                    'card_brand' => $card->cardBrand()->value,
                    'card_last_four' => $card->cardLastFour(),
                    'card_expiry_month' => $card->cardExpiryMonth(),
                    'card_expiry_year' => $card->cardExpiryYear(),
                ]);
            }

            if ($method->bankAccount() !== null) {
                $account = $method->bankAccount();
                BankAccountModel::create([
                    'payment_method_id' => $model->id,
                    'account_holder_name' => $account->accountHolderName(),
                    'iban_masked' => $account->ibanMasked(),
                    'iban_last_four' => $account->ibanLastFour(),
                    'iban_full' => $account->ibanFull(),
                    'bank_name' => $account->bankName(),
                ]);
            }

            return $model->id;
        });
    }

    public function findById(int $id): PaymentMethod
    {
        $model = PaymentMethodModel::with(['creditCard', 'bankAccount'])->find($id);

        if ($model === null) {
            throw new PaymentMethodNotFoundException($id);
        }

        return $this->toDomain($model);
    }

    public function findByCustomerId(int $customerId): array
    {
        return PaymentMethodModel::with(['creditCard', 'bankAccount'])
            ->where('customer_id', $customerId)
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($m) => $this->toDomain($m))
            ->all();
    }

    public function delete(int $id): void
    {
        PaymentMethodModel::findOrFail($id)->delete();
    }

    public function clearDefaultForCustomer(int $customerId): void
    {
        PaymentMethodModel::where('customer_id', $customerId)
            ->where('is_default', true)
            ->update(['is_default' => false]);
    }

    public function setDefault(int $paymentMethodId, int $customerId): void
    {
        DB::transaction(function () use ($paymentMethodId, $customerId) {
            PaymentMethodModel::where('customer_id', $customerId)
                ->update(['is_default' => false]);

            PaymentMethodModel::where('id', $paymentMethodId)
                ->update(['is_default' => true]);
        });
    }

    private function toDomain(PaymentMethodModel $model): PaymentMethod
    {
        $creditCard = null;
        $bankAccount = null;

        if ($model->creditCard !== null) {
            $c = $model->creditCard;
            $creditCard = CreditCard::fromPrimitives(
                id: $c->id,
                paymentMethodId: $c->payment_method_id,
                cardHolderName: $c->card_holder_name,
                cardBrand: $c->card_brand,
                cardLastFour: $c->card_last_four,
                cardExpiryMonth: $c->card_expiry_month,
                cardExpiryYear: $c->card_expiry_year,
            );
        }

        if ($model->bankAccount !== null) {
            $b = $model->bankAccount;
            $bankAccount = BankAccount::fromPrimitives(
                id: $b->id,
                paymentMethodId: $b->payment_method_id,
                accountHolderName: $b->account_holder_name,
                ibanMasked: $b->iban_masked,
                ibanLastFour: $b->iban_last_four,
                ibanFull: $b->iban_full,
                bankName: $b->bank_name,
            );
        }

        return PaymentMethod::fromPrimitives(
            id: $model->id,
            customerId: $model->customer_id,
            type: $model->type,
            isDefault: $model->is_default,
            label: $model->label ?? '',
            creditCard: $creditCard,
            bankAccount: $bankAccount,
        );
    }
}
