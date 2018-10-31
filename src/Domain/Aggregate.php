<?php

declare(strict_types=1);

namespace Todo\Domain;

abstract class Aggregate
{
    private $recordedEvents = [];

    protected function recordThat($event): void
    {
        $this->recordedEvents[] = $event;
    }

    public function releaseEvents(): array
    {
        $recordedEvents = $this->recordedEvents;
        $this->recordedEvents = [];

        return $recordedEvents;
    }
}
