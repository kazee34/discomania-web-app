<?php

namespace Src\admin\customer\infrastructure\controllers;

use App\Http\Controllers\Controller;
use App\Rules\NoSpecialCharacters;
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
            'first_name' => ['required', 'string', 'min:2', 'max:100', new NoSpecialCharacters],
            'last_name' => ['required', 'string', 'min:2', 'max:100', new NoSpecialCharacters],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'regex:/^\+?[\d\s\-]{6,20}$/'],
            'birth_date' => ['nullable', 'date', 'before:-16 years'],
            'dni_nif' => ['nullable', 'string', 'max:20', new NoSpecialCharacters],
            'shipping_street' => ['required', 'string', 'max:255', new NoSpecialCharacters],
            'shipping_street_number' => ['required', 'string', 'max:20', new NoSpecialCharacters],
            'shipping_apartment' => ['nullable', 'string', 'max:50', new NoSpecialCharacters],
            'shipping_city' => ['required', 'string', 'max:100', new NoSpecialCharacters],
            'shipping_postal_code' => ['required', 'string', 'max:20', new NoSpecialCharacters],
            'shipping_state_province' => ['nullable', 'string', 'max:100', new NoSpecialCharacters],
            'is_vip' => ['nullable', 'boolean'],
        ], [
            'first_name.required'             => 'El nombre es obligatorio.',
            'first_name.min'                  => 'El nombre debe tener al menos 2 caracteres.',
            'first_name.max'                  => 'El nombre no puede superar los 100 caracteres.',
            'last_name.required'              => 'Los apellidos son obligatorios.',
            'last_name.min'                   => 'Los apellidos deben tener al menos 2 caracteres.',
            'last_name.max'                   => 'Los apellidos no pueden superar los 100 caracteres.',
            'email.required'                  => 'El correo electrónico es obligatorio.',
            'email.email'                     => 'El formato del correo electrónico no es válido.',
            'email.unique'                    => 'Este correo electrónico ya está registrado.',
            'password.required'               => 'La contraseña es obligatoria.',
            'password.min'                    => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'              => 'La confirmación de contraseña no coincide.',
            'phone.regex'                     => 'El formato del teléfono no es válido (ej: +34 600 000 000).',
            'birth_date.before'               => 'El cliente debe tener al menos 16 años.',
            'shipping_street.required'        => 'La calle es obligatoria.',
            'shipping_street.max'             => 'La calle no puede superar los 255 caracteres.',
            'shipping_street_number.required' => 'El número de la calle es obligatorio.',
            'shipping_street_number.max'      => 'El número no puede superar los 20 caracteres.',
            'shipping_apartment.max'          => 'El piso/puerta no puede superar los 50 caracteres.',
            'shipping_city.required'          => 'La ciudad es obligatoria.',
            'shipping_city.max'               => 'La ciudad no puede superar los 100 caracteres.',
            'shipping_postal_code.required'   => 'El código postal es obligatorio.',
            'shipping_postal_code.max'        => 'El código postal no puede superar los 20 caracteres.',
            'shipping_state_province.max'     => 'La provincia no puede superar los 100 caracteres.',
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
