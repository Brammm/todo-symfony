<?php

declare(strict_types=1);

namespace Todo\Tests\Unit\Domain\User;

use PHPUnit\Framework\TestCase;
use Todo\Domain\User\UserWasRegistered;
use Todo\Tests\Builder\UserBuilder;

final class UserTest extends TestCase
{
    public function testItRecordsEventAfterRegistering()
    {
        $user = UserBuilder::withDefaults()->build();

        $events = $user->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserWasRegistered::class, array_shift($events));
    }
}
