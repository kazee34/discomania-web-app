<?php

namespace Src\customer\user\infrastructure\repositories;

use App\Models\CustomerModel;
use App\Models\UserModel;
use Src\customer\user\domain\entities\Customer;
use Src\customer\user\domain\exceptions\CustomerNotFoundException;
use Src\customer\user\domain\repositories\CustomerRepositoryInterface;
use Src\customer\user\domain\valueObjects\CustomerDNI;
use Src\customer\user\domain\valueObjects\CustomerPhone;
use Src\customer\user\domain\valueObjects\CustomerPreferences;
use Src\customer\user\domain\valueObjects\ShippingAddress;
use Src\customer\user\domain\valueObjects\TaxInformation;
use Src\shared\domain\valueObjects\UserName;

class EloquentCustomerRepository implements CustomerRepositoryInterface
{
    public function save(Customer $customer): void
    {
        $model = new CustomerModel;
        $this->hydrateModel($model, $customer);
        $model->save();
    }

    public function findById(int $id): ?Customer
    {
        $model = CustomerModel::find($id);

        return $model ? $this->toCustomer($model) : null;
    }

    public function findByUserId(int $userId): ?Customer
    {
        $model = CustomerModel::where('user_id', $userId)->first();

        return $model ? $this->toCustomer($model) : null;
    }

    public function update(Customer $customer): void
    {
        if ($customer->id() === null) {
            throw new \RuntimeException('Cannot update customer without ID');
        }

        $model = CustomerModel::query()->findOrFail($customer->id());
        $this->hydrateModel($model, $customer);
        $model->save();

        UserModel::query()->where('id', $customer->userId())->update([
            'name' => $customer->firstName()->value().' '.$customer->lastName()->value(),
        ]);
    }

    public function delete(int $id): void
    {
        if (! CustomerModel::query()->where('id', $id)->exists()) {
            throw CustomerNotFoundException::byId($id);
        }
        CustomerModel::query()->delete($id);
    }

    public function exists(int $id): bool
    {
        return CustomerModel::query()->where('id', $id)->exists();
    }

    public function searchAll(): array
    {
        return CustomerModel::query()->get()->map(fn ($model) => $this->toCustomer($model))->toArray();
    }

    public function paginate(int $page = 1, int $perPage = 15): array
    {
        $paginated = CustomerModel::query()->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $paginated->map(fn ($model) => $this->toCustomer($model))->toArray(),
            'total' => $paginated->total(),
            'per_page' => $paginated->perPage(),
            'current_page' => $paginated->currentPage(),
            'last_page' => $paginated->lastPage(),
        ];
    }

    private function toCustomer(CustomerModel $model): Customer
    {
        $shippingAddress = new ShippingAddress(
            $model->shipping_street ?? '',
            $model->shipping_street_number ?? '',
            $model->shipping_apartment,
            $model->shipping_city ?? '',
            $model->shipping_postal_code ?? '',
            $model->shipping_state_province ?? '',
            $model->shipping_country ?? 'España',
            $model->shipping_iso_country_code ?? 'ES',
        );

        $taxInformation = new TaxInformation(
            $model->tax_name,
            $model->tax_vat_number,
        );

        $preferences = new CustomerPreferences(
            $model->preferred_language ?? 'es',
            $model->preferred_currency ?? 'EUR',
            $model->wishlist ?? [],
        );

        return new Customer(
            id: $model->id,
            userId: $model->user_id,
            firstName: new UserName($model->first_name),
            lastName: new UserName($model->last_name),
            phone: new CustomerPhone($model->phone),
            birthDate: $model->birth_date?->format('Y-m-d'),
            dniNif: new CustomerDNI($model->dni_nif),
            shippingAddress: $shippingAddress,
            taxInformation: $taxInformation,
            totalOrders: $model->total_orders,
            preferences: $preferences,
            isActive: $model->is_active ?? true,
            isVip: $model->is_vip,
            createdAt: $model->created_at,
            updatedAt: $model->updated_at,
        );
    }

    private function hydrateModel(CustomerModel $model, Customer $customer): void
    {
        $model->user_id = $customer->userId();
        $model->is_active = $customer->isActive();
        $model->is_vip = $customer->isVip();
        $model->first_name = $customer->firstName()->value();
        $model->last_name = $customer->lastName()->value();
        $model->phone = $customer->phone()->value();
        $model->birth_date = $customer->birthDate();
        $model->dni_nif = $customer->dniNif()->value();

        $address = $customer->shippingAddress();
        $model->shipping_street = $address->street();
        $model->shipping_street_number = $address->streetNumber();
        $model->shipping_apartment = $address->apartment();
        $model->shipping_city = $address->city();
        $model->shipping_postal_code = $address->postalCode();
        $model->shipping_state_province = $address->stateProvince();
        $model->shipping_country = $address->country();
        $model->shipping_iso_country_code = $address->isoCountryCode();

        $tax = $customer->taxInformation();
        $model->tax_name = $tax->name();
        $model->tax_vat_number = $tax->vatNumber();

        $model->total_orders = $customer->totalOrders();

        $prefs = $customer->preferences();
        $model->wishlist = $prefs->wishlist();
        $model->preferred_language = $prefs->preferredLanguage();
        $model->preferred_currency = $prefs->preferredCurrency();
    }
}
