<?php

namespace Src\customer\user\application\dto;

class CreateCustomerRequest
{
    public function __construct(
        public readonly int $userId,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $shippingStreet,
        public readonly string $shippingStreetNumber,
        public readonly string $shippingCity,
        public readonly string $shippingPostalCode,
        public readonly string $shippingCountry,
        public readonly string $shippingIsoCountryCode,
        public readonly ?string $phone = null,
        public readonly ?string $birthDate = null,
        public readonly ?string $dniNif = null,
        public readonly ?string $shippingApartment = null,
        public readonly ?string $shippingStateProvince = null,
        public readonly ?string $taxName = null,
        public readonly ?string $taxVatNumber = null,
        public readonly string $preferredLanguage = 'es',
        public readonly string $preferredCurrency = 'EUR',
        public readonly array $wishlist = [],
    ) {}
}
