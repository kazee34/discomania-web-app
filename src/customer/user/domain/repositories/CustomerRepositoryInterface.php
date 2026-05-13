<?php

namespace Src\customer\user\domain\repositories;

use Src\customer\user\domain\entities\Customer;

interface CustomerRepositoryInterface
{
    /**
     * Save a customer to the repository
     */
    public function save(Customer $customer): void;

    /**
     * Retrieve a customer by ID
     */
    public function findById(int $id): ?Customer;

    /**
     * Retrieve a customer by User ID
     */
    public function findByUserId(int $userId): ?Customer;

    /**
     * Update an existing customer
     */
    public function update(Customer $customer): void;

    /**
     * Delete a customer
     */
    public function delete(int $id): void;

    /**
     * Check if a customer exists by ID
     */
    public function exists(int $id): bool;

    /**
     * Get all customers
     */
    public function searchAll(): array;

    /**
     * Get customers with pagination
     */
    public function paginate(int $page = 1, int $perPage = 15): array;
}
