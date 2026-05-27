<?php

namespace Src\admin\user\application\listeners;

use Illuminate\Support\Facades\DB;
use Src\admin\user\domain\events\AdminDeactivatedEvent;

class LogoutUserOnAdminDeactivatedListener
{
    public function handle(AdminDeactivatedEvent $event): void
    {
        DB::table('sessions')->where('user_id', $event->userId)->delete();

        logger()->info('Sessions invalidated after admin deactivation', [
            'admin_id' => $event->id,
            'user_id' => $event->userId,
        ]);
    }
}
