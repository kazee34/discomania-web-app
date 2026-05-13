<?php

namespace Src\shared\domain\repositories;

interface EventPublisher
{
    public function publish(object $event): void;

    public function publishAll(array $events): void;
}
