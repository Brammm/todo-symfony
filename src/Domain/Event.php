<?php

declare(strict_types=1);

namespace Todo\Domain;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

abstract class Event extends BaseEvent
{
    public function getName(): string
    {
        $parts = explode('\\', static::class);

        return array_pop($parts);
    }
}
