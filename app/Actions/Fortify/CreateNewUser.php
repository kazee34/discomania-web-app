<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Rules\NoSpecialCharacters;
use App\Models\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Src\admin\user\application\useCases\CreateUserUseCase;
use Src\customer\user\application\dto\CreateCustomerRequest;
use Src\customer\user\application\useCases\CreateCustomerUseCase;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function __construct(
        private CreateUserUseCase $createUserUseCase,
        private CreateCustomerUseCase $createCustomerUseCase,
    ) {}

    public function create(array $input): UserModel
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'min:3', 'max:100', new NoSpecialCharacters],
            'last_name' => ['required', 'string', 'min:3', 'max:100', new NoSpecialCharacters],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(UserModel::class, 'email')],
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers(), 'confirmed'],
            'phone' => ['nullable', 'string', 'regex:/^\+?[\d\s\-]{6,20}$/'],
            'birth_date' => ['nullable', 'date', 'before:-16 years'],
            'dni_nif' => ['nullable', 'string', 'max:20', new NoSpecialCharacters],
            'shipping_street' => ['required', 'string', 'max:255', new NoSpecialCharacters],
            'shipping_street_number' => ['required', 'string', 'max:20', new NoSpecialCharacters],
            'shipping_apartment' => ['nullable', 'string', 'max:50', new NoSpecialCharacters],
            'shipping_city' => ['required', 'string', 'regex:/^[\pL\s\-\.]+$/u', 'max:100', new NoSpecialCharacters],
            'shipping_postal_code' => ['required', 'string', 'max:20', new NoSpecialCharacters],
            'shipping_state_province' => ['nullable', 'string', 'regex:/^[\pL\s\-\.]+$/u', 'max:100', new NoSpecialCharacters],
        ], [
            'first_name.required'             => 'El nombre es obligatorio.',
            'first_name.min'                  => 'El nombre debe tener al menos 3 caracteres.',
            'first_name.max'                  => 'El nombre no puede superar los 100 caracteres.',
            'last_name.required'              => 'Los apellidos son obligatorios.',
            'last_name.min'                   => 'Los apellidos deben tener al menos 3 caracteres.',
            'last_name.max'                   => 'Los apellidos no pueden superar los 100 caracteres.',
            'email.required'                  => 'El correo electrónico es obligatorio.',
            'email.email'                     => 'El formato del correo electrónico no es válido.',
            'email.unique'                    => 'Este correo electrónico ya está registrado.',
            'password.required'               => 'La contraseña es obligatoria.',
            'password.confirmed'              => 'La confirmación de contraseña no coincide.',
            'phone.regex'                     => 'El formato del teléfono no es válido.',
            'birth_date.before'               => 'Debes tener al menos 16 años para registrarte.',
            'shipping_street.required'        => 'La calle es obligatoria.',
            'shipping_street_number.required' => 'El número de la calle es obligatorio.',
            'shipping_city.required'          => 'La ciudad es obligatoria.',
            'shipping_city.regex'             => 'La ciudad solo puede contener letras y guiones.',
            'shipping_postal_code.required'   => 'El código postal es obligatorio.',
        ])->validate();

        return DB::transaction(function () use ($input) {
            $result = $this->createUserUseCase->execute(
                name: $input['first_name'].' '.$input['last_name'],
                email: $input['email'],
                password: $input['password'],
                role: '',
            );

            $user = UserModel::findOrFail($result->id);

            $this->createCustomerUseCase->execute(new CreateCustomerRequest(
                userId: $result->id,
                firstName: $input['first_name'],
                lastName: $input['last_name'],
                shippingStreet: $input['shipping_street'],
                shippingStreetNumber: $input['shipping_street_number'],
                shippingCity: $input['shipping_city'],
                shippingPostalCode: $input['shipping_postal_code'],
                shippingCountry: 'España',
                shippingIsoCountryCode: 'ES',
                phone: $input['phone'] ?? null,
                birthDate: $input['birth_date'] ?? null,
                dniNif: $input['dni_nif'] ?? null,
                shippingApartment: $input['shipping_apartment'] ?? null,
                shippingStateProvince: $input['shipping_state_province'] ?? null,
            ));

            return $user;
        });
    }
}
