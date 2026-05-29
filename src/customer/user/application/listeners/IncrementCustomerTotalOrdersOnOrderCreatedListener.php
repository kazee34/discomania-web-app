<?php

namespace Src\customer\user\application\listeners;

use App\Models\CustomerModel;
use Src\customer\order\domain\events\OrderCreatedEvent;

class IncrementCustomerTotalOrdersOnOrderCreatedListener
{
    public function handle(OrderCreatedEvent $event): void
    {
        CustomerModel::where('id', $event->customerId)->increment('total_orders');
    }
}
