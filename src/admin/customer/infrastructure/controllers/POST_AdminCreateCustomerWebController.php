<?php

namespace Src\admin\customer\infrastructure\controllers;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Src\admin\user\application\useCases\CreateUserUseCase;
use Src\customer\user\application\dto\CreateCustomerRequest;
use Src\customer\user\application\useCases\CreateCustomerUseCase;

final class POST_AdminCreateCustomerWebController extends Controller
{
    public function __construct(
        private CreateUserUseCase $createUserUseCase,
        private CreateCustomerUseCase $createCustomerUseCase,
    ) {}

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'min:2', 'max:100'],
            'last_name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', Rule::unique(UserModel::class, 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:50'],
            'birth_date' => ['nullable', 'date', 'before:-16 years'],
            'dni_nif' => ['nullable', 'string', 'max:20'],
            'shipping_street' => ['required', 'string', 'max:255'],
            'shipping_street_number' => ['required', 'string', 'max:20'],
            'shipping_apartment' => ['nullable', 'string', 'max:50'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_postal_code' => ['required', 'string', 'max:20'],
            'shipping_state_province' => ['nullable', 'string', 'max:100'],
            'is_vip' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($data) {
            $result = $this->createUserUseCase->execute(
                name: $data['first_name'].' '.$data['last_name'],
                email: $data['email'],
                password: $data['password'],
                role: '',
            );

            $this->createCustomerUseCase->execute(new CreateCustomerRequest(
                userId: $result->id,
                firstName: $data['first_name'],
                lastName: $data['last_name'],
                shippingStreet: $data['shipping_street'],
                shippingStreetNumber: $data['shipping_street_number'],
                shippingCity: $data['shipping_city'],
                shippingPostalCode: $data['shipping_postal_code'],
                shippingCountry: 'España',
                shippingIsoCountryCode: 'ES',
                phone: $data['phone'] ?? null,
                birthDate: $data['birth_date'] ?? null,
                dniNif: $data['dni_nif'] ?? null,
                shippingApartment: $data['shipping_apartment'] ?? null,
                shippingStateProvince: $data['shipping_state_province'] ?? null,
                isVip: (bool) ($data['is_vip'] ?? false),
            ));
        });

        return redirect()->route('admin.customers.index')
            ->with('success', 'Cliente creado correctamente.');
    }
}
