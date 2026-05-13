<?php

namespace Src\customer\user\application\dto;

class CreateCustomerRequest
{
    public function __construct(
        public readonly int $userId,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $phone,
        public readonly string $birthDate,
        public readonly ?string $dniNif = null,
        public readonly string $shippingStreet,
        public readonly string $shippingStreetNumber,
        public readonly string $shippingApartment,
        public readonly string $shippingCity,
        public readonly string $shippingPostalCode,
        public readonly string $shippingStateProvince,
        public readonly string $shippingCountry,
        public readonly ?string $shippingIsoCountryCode,
        public readonly ?string $taxName = null,
        public readonly ?string $taxVatNumber = null,
        public readonly ?string $preferredLanguage = null,
        public readonly ?string $preferredCurrency = null,
        public readonly array $wishlist = [],
    ) {}
}
