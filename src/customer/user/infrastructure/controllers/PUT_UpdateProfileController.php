<?php

namespace Src\customer\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Src\customer\user\application\useCases\UpdateCustomerProfileUseCase;

final class PUT_UpdateProfileController extends Controller
{
    public function __construct(
        private UpdateCustomerProfileUseCase $updateProfile,
    ) {}

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'firstName' => ['required', 'string', 'min:3', 'max:100'],
            'lastName' => ['required', 'string', 'min:3', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'birthDate' => ['nullable', 'date', 'after_or_equal:1900-01-01', 'before_or_equal:'.now()->subYears(16)->format('Y-m-d')],
            'shippingStreet' => ['required', 'string', 'max:200'],
            'shippingStreetNumber' => ['required', 'string', 'max:20'],
            'shippingApartment' => ['nullable', 'string', 'max:50'],
            'shippingCity' => ['required', 'string', 'max:100'],
            'shippingPostalCode' => ['required', 'string', 'max:20'],
            'shippingStateProvince' => ['nullable', 'string', 'max:100'],
            'shippingCountry' => ['required', 'string', 'max:100'],
        ]);

        $this->updateProfile->execute(
            userId: Auth::id(),
            firstName: $data['firstName'],
            lastName: $data['lastName'],
            phone: $data['phone'] ?? null,
            birthDate: $data['birthDate'] ?? null,
            shippingStreet: $data['shippingStreet'],
            shippingStreetNumber: $data['shippingStreetNumber'],
            shippingApartment: $data['shippingApartment'] ?? null,
            shippingCity: $data['shippingCity'],
            shippingPostalCode: $data['shippingPostalCode'],
            shippingStateProvince: $data['shippingStateProvince'] ?? null,
            shippingCountry: $data['shippingCountry'],
        );

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
