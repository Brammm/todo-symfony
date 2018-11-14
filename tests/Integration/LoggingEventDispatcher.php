<?php

declare(strict_types=1);

namespace Todo\Tests\Integration;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class LoggingEventDispatcher implements EventDispatcherInterface
{
    private $dispatchedEvents = [];

    public function dispatch($eventName, Event $event = null)
    {
        $this->dispatchedEvents[] = $event;
    }

    public function getDispatchedEvents(): array
    {
        return $this->dispatchedEvents;
    }

    public function addListener($eventName, $listener, $priority = 0)
    {
        // TODO: Implement addListener() method.
    }

    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        // TODO: Implement addSubscriber() method.
    }

    public function removeListener($eventName, $listener)
    {
        // TODO: Implement removeListener() method.
    }

    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        // TODO: Implement removeSubscriber() method.
    }

    public function getListeners($eventName = null)
    {
        // TODO: Implement getListeners() method.
    }

    public function getListenerPriority($eventName, $listener)
    {
        // TODO: Implement getListenerPriority() method.
    }

    public function hasListeners($eventName = null)
    {
        // TODO: Implement hasListeners() method.
    }
}
