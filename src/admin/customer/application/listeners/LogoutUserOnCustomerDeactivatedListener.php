<?php

namespace Src\admin\customer\application\listeners;

use Illuminate\Support\Facades\DB;
use Src\customer\user\domain\events\CustomerDeactivatedEvent;

class LogoutUserOnCustomerDeactivatedListener
{
    public function handle(CustomerDeactivatedEvent $event): void
    {
        DB::table('sessions')->where('user_id', $event->userId)->delete();

        logger()->info('Sessions invalidated after customer deactivation', [
            'customer_id' => $event->customerId,
            'user_id' => $event->userId,
        ]);
    }
}
