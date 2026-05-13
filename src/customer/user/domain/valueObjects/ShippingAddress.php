<?php

namespace Src\customer\user\domain\valueObjects;

use InvalidArgumentException;

class ShippingAddress
{
    public function __construct(
        private readonly string $street,
        private readonly string $streetNumber,
        private readonly ?string $apartment,
        private readonly string $city,
        private readonly string $postalCode,
        private readonly string $stateProvince,
        private readonly string $country,
        private readonly string $isoCountryCode,
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if (empty($this->street)) {
            throw new InvalidArgumentException('Street cannot be empty');
        }
        if (empty($this->streetNumber)) {
            throw new InvalidArgumentException('Street number cannot be empty');
        }
        if (empty($this->city)) {
            throw new InvalidArgumentException('City cannot be empty');
        }
        if (empty($this->postalCode)) {
            throw new InvalidArgumentException('Postal code cannot be empty');
        }
        if (strlen($this->isoCountryCode) !== 2) {
            throw new InvalidArgumentException('ISO country code must be 2 characters');
        }
    }

    public function street(): string
    {
        return $this->street;
    }

    public function streetNumber(): string
    {
        return $this->streetNumber;
    }

    public function apartment(): ?string
    {
        return $this->apartment;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function postalCode(): string
    {
        return $this->postalCode;
    }

    public function stateProvince(): string
    {
        return $this->stateProvince;
    }

    public function country(): string
    {
        return $this->country;
    }

    public function isoCountryCode(): string
    {
        return $this->isoCountryCode;
    }

    public function toArray(): array
    {
        return [
            'street' => $this->street,
            'street_number' => $this->streetNumber,
            'apartment' => $this->apartment,
            'city' => $this->city,
            'postal_code' => $this->postalCode,
            'state_province' => $this->stateProvince,
            'country' => $this->country,
            'iso_country_code' => $this->isoCountryCode,
        ];
    }
}
