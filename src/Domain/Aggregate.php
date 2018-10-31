<?php

declare(strict_types=1);

namespace Todo\Domain;

abstract class Aggregate
{
    /**
     * @var Event[]
     */
    private $recordedEvents = [];

    protected function recordThat(Event $event): void
    {
        $this->recordedEvents[] = $event;
    }

    /**
     * @return Event[]
     */
    public function releaseEvents(): array
    {
        $recordedEvents = $this->recordedEvents;
        $this->recordedEvents = [];

        return $recordedEvents;
    }
}
