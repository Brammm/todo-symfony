<?php

declare(strict_types=1);

namespace Todo\Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use Todo\Domain\Event;

class EventTest extends TestCase
{
    public function testItReturnsShortName(): void
    {
        $event = new FooEvent();

        $this->assertEquals('FooEvent', $event->getName());
    }
}

class FooEvent extends Event
{
}
