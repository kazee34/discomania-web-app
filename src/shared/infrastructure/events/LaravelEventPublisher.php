<?php

namespace Src\shared\infrastructure\events;

use Illuminate\Contracts\Events\Dispatcher;
use Src\shared\domain\repositories\EventPublisher;

class LaravelEventPublisher implements EventPublisher
{
    public function __construct(
        private Dispatcher $laravelDispatcher
    ) {}

    public function publish(object $event): void
    {
        $this->laravelDispatcher->dispatch($event);
    }

    public function publishAll(array $events): void
    {
        foreach ($events as $event) {
            $this->publish($event);
        }
    }
}
