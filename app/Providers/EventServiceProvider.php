<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Src\admin\customer\application\listeners\LogoutUserOnCustomerDeactivatedListener;
use Src\admin\user\application\listeners\CreateAdminOnUserCreatedListener;
use Src\admin\user\application\listeners\DeleteUserOnAdminDeletedListener;
use Src\admin\user\application\listeners\LogoutUserOnAdminDeactivatedListener;
use Src\admin\user\domain\events\AdminDeactivatedEvent;
use Src\admin\user\domain\events\AdminDeletedEvent;
use Src\customer\order\application\listeners\SendOrderConfirmationEmailOnOrderCreatedListener;
use Src\customer\order\application\listeners\SendOrderStatusUpdateEmailListener;
use Src\customer\order\domain\events\OrderCancelledEvent;
use Src\customer\order\domain\events\OrderCreatedEvent;
use Src\customer\order\domain\events\OrderStatusUpdatedEvent;
use Src\customer\payment\application\listeners\OrderPaymentRefundOnCancelledOrderListener;
use Src\customer\user\application\listeners\SendWelcomeEmailOnCustomerCreatedListener;
use Src\customer\user\domain\events\CustomerCreatedEvent;
use Src\customer\user\domain\events\CustomerDeactivatedEvent;
use Src\shared\domain\events\UserCreatedEvent;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AdminDeletedEvent::class => [
            DeleteUserOnAdminDeletedListener::class,
        ],
        AdminDeactivatedEvent::class => [
            LogoutUserOnAdminDeactivatedListener::class,
        ],
        CustomerDeactivatedEvent::class => [
            LogoutUserOnCustomerDeactivatedListener::class,
        ],
        UserCreatedEvent::class => [
            CreateAdminOnUserCreatedListener::class,
        ],
        OrderCancelledEvent::class => [
            OrderPaymentRefundOnCancelledOrderListener::class,
        ],
        CustomerCreatedEvent::class => [
            SendWelcomeEmailOnCustomerCreatedListener::class,
        ],
        OrderCreatedEvent::class => [
            SendOrderConfirmationEmailOnOrderCreatedListener::class,
        ],
        OrderStatusUpdatedEvent::class => [
            SendOrderStatusUpdateEmailListener::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
        logger()->info('EventServiceProvider booted - Listeners registered: ', ['listeners' => array_keys($this->listen)]);
        logger()->info('EventServiceProvider booted');
    }
}
