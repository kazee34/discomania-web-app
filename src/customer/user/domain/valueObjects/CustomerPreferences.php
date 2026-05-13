<?php

namespace Src\customer\user\domain\valueObjects;

use InvalidArgumentException;

class CustomerPreferences
{
    private const VALID_LANGUAGES = ['es', 'en', 'fr', 'de', 'it', 'pt'];

    private const VALID_CURRENCIES = ['EUR', 'USD', 'GBP', 'JPY', 'CHF', 'CAD', 'AUD'];

    public function __construct(
        private readonly string $preferredLanguage,
        private readonly string $preferredCurrency,
        private readonly array $wishlist = [],
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        if (! in_array($this->preferredLanguage, self::VALID_LANGUAGES, true)) {
            throw new InvalidArgumentException("Invalid language: {$this->preferredLanguage}");
        }
        if (! in_array($this->preferredCurrency, self::VALID_CURRENCIES, true)) {
            throw new InvalidArgumentException("Invalid currency: {$this->preferredCurrency}");
        }
    }

    public function preferredLanguage(): string
    {
        return $this->preferredLanguage;
    }

    public function preferredCurrency(): string
    {
        return $this->preferredCurrency;
    }

    public function wishlist(): array
    {
        return $this->wishlist;
    }

    public function addToWishlist(int $productId): self
    {
        if (! in_array($productId, $this->wishlist, true)) {
            $wishlist = $this->wishlist;
            $wishlist[] = $productId;

            return new self($this->preferredLanguage, $this->preferredCurrency, $wishlist);
        }

        return $this;
    }

    public function removeFromWishlist(int $productId): self
    {
        $wishlist = array_filter($this->wishlist, fn ($id) => $id !== $productId);

        return new self($this->preferredLanguage, $this->preferredCurrency, array_values($wishlist));
    }

    public function toArray(): array
    {
        return [
            'preferred_language' => $this->preferredLanguage,
            'preferred_currency' => $this->preferredCurrency,
            'wishlist' => $this->wishlist,
        ];
    }
}
