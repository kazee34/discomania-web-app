<?php

namespace Src\admin\user\infrastructure\events;

use Illuminate\Support\Facades\DB;
use Src\shared\domain\repositories\EventPublisher;

class LaravelEventPublisher implements EventPublisher
{
    public function publish(object $event): void
    {
        if (DB::transactionLevel() > 0) {
            DB::afterCommit(fn () => app('events')->dispatch($event));
        } else {
            app('events')->dispatch($event);
        }
    }

    public function publishAll(array $events): void
    {
        foreach ($events as $event) {
            $this->publish($event);
        }
    }
}
