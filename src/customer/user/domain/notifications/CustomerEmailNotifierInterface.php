<?php

namespace Src\customer\user\domain\notifications;

interface CustomerEmailNotifierInterface
{
    public function sendWelcome(string $email, string $firstName, string $lastName): void;
}
