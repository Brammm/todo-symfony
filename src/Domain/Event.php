<?php

declare(strict_types=1);

namespace Todo\Domain;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

abstract class Event extends BaseEvent
{
    public function getName(): string
    {
        return static::class;
    }
}
