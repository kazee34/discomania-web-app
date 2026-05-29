<?php

namespace Src\customer\cart\application\listeners;

use App\Models\CartModel;
use App\Models\CustomerModel;
use Illuminate\Auth\Events\Logout;

class ClearCartOnUserLogoutListener
{
    public function handle(Logout $event): void
    {
        $customer = CustomerModel::where('user_id', $event->user->id)->first();
        if (! $customer) {
            return;
        }

        CartModel::where('customer_id', $customer->id)->each(function (CartModel $cart): void {
            $cart->items()->delete();
            $cart->delete();
        });
    }
}
