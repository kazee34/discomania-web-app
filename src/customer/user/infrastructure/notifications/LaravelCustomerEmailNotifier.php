<?php

namespace Src\customer\user\infrastructure\notifications;

use App\Mail\CustomerWelcomeMail;
use Illuminate\Support\Facades\Mail;
use Src\customer\user\domain\notifications\CustomerEmailNotifierInterface;

class LaravelCustomerEmailNotifier implements CustomerEmailNotifierInterface
{
    public function sendWelcome(string $email, string $firstName, string $lastName): void
    {
        Mail::to($email)->send(new CustomerWelcomeMail("{$firstName} {$lastName}"));
    }
}
