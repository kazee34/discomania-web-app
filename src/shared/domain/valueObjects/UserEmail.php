<?php

namespace src\shared\domain\valueObjects;

use InvalidArgumentException;

class UserEmail
{
    public function __construct(
        private string $email
    ) {
        $this->validate($email);
        $this->email = $email;
    }

    private function validate(string $email): void
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }
    }

    public function value(): string
    {
        return $this->email;
    }
}
