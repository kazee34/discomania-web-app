<?php

namespace Src\admin\customer\infrastructure\repositories;

use App\Models\CustomerModel;
use Src\admin\customer\domain\repositories\AdminCustomerRepositoryInterface;
use Src\customer\user\domain\entities\Customer;
use Src\customer\user\domain\valueObjects\CustomerDNI;
use Src\customer\user\domain\valueObjects\CustomerPhone;
use Src\customer\user\domain\valueObjects\CustomerPreferences;
use Src\customer\user\domain\valueObjects\ShippingAddress;
use Src\customer\user\domain\valueObjects\TaxInformation;
use Src\shared\domain\valueObjects\UserName;

class EloquentAdminCustomerRepository implements AdminCustomerRepositoryInterface
{
    public function searchAll(): array
    {
        return CustomerModel::query()
            ->withCount('orders')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($model) => $this->toCustomer($model))
            ->all();
    }

    public function findById(int $id): ?Customer
    {
        $model = CustomerModel::query()->withCount('orders')->find($id);

        return $model ? $this->toCustomer($model) : null;
    }

    public function update(Customer $customer): void
    {
        CustomerModel::query()
            ->where('id', $customer->id())
            ->update([
                'is_active' => $customer->isActive(),
                'is_vip' => $customer->isVip(),
            ]);
    }

    private function toCustomer(CustomerModel $model): Customer
    {
        return new Customer(
            id: $model->id,
            userId: $model->user_id,
            firstName: new UserName($model->first_name),
            lastName: new UserName($model->last_name),
            phone: new CustomerPhone($model->phone),
            birthDate: $model->birth_date?->format('Y-m-d'),
            dniNif: new CustomerDNI($model->dni_nif),
            shippingAddress: new ShippingAddress(
                $model->shipping_street ?? '',
                $model->shipping_street_number ?? '',
                $model->shipping_apartment,
                $model->shipping_city ?? '',
                $model->shipping_postal_code ?? '',
                $model->shipping_state_province ?? '',
                $model->shipping_country ?? 'España',
                $model->shipping_iso_country_code ?? 'ES',
            ),
            taxInformation: new TaxInformation(
                $model->tax_name,
                $model->tax_vat_number,
            ),
            totalOrders: $model->orders_count ?? $model->total_orders,
            preferences: new CustomerPreferences(
                $model->preferred_language ?? 'es',
                $model->preferred_currency ?? 'EUR',
                $model->wishlist ?? [],
            ),
            isActive: $model->is_active ?? true,
            isVip: $model->is_vip ?? false,
            createdAt: $model->created_at,
            updatedAt: $model->updated_at,
        );
    }
}
