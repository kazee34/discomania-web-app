<?php

namespace Src\admin\user\domain\valueObjects;

use InvalidArgumentException;

class UserPassword
{
    private string $hash;

    private function __construct() {}

    public static function fromPlain(string $plainPassword): self
    {
        $self = new self;
        $self->validate($plainPassword);
        $self->hash = password_hash($plainPassword, PASSWORD_BCRYPT);

        return $self;
    }

    public static function fromHash(string $hash): self
    {
        $self = new self;
        $self->hash = $hash;

        return $self;
    }

    private function validate(string $plainPassword): void
    {
        if (strlen($plainPassword) < 8) {
            throw new InvalidArgumentException('Password must be at least 8 characters');
        }

        if (! preg_match('/[A-Z]/', $plainPassword)) {
            throw new InvalidArgumentException('Password must contain at least one uppercase letter.');
        }

        if (! preg_match('/[0-9]/', $plainPassword)) {
            throw new InvalidArgumentException('Password must contain at least one number.');
        }
    }

    public function verify(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hash);
    }

    public function hash(): string
    {
        return $this->hash;
    }

    public function __serialize(): array
    {
        return ['hash' => $this->hash];
    }

    public function __toString(): string
    {
        return '********';
    }
}
