<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Src\admin\user\application\listeners\CreateAdminOnUserCreatedListener;
use Src\admin\user\application\listeners\DeleteUserOnAdminDeletedListener;
use Src\admin\user\domain\events\AdminDeletedEvent;
use Src\shared\domain\events\UserCreatedEvent;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AdminDeletedEvent::class => [
            DeleteUserOnAdminDeletedListener::class,
        ],
        UserCreatedEvent::class => [
            CreateAdminOnUserCreatedListener::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
        logger()->info('EventServiceProvider booted - Listeners registered: ', ['listeners' => array_keys($this->listen)]);
        logger()->info('EventServiceProvider booted');
    }
}
