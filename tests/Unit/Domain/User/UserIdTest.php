<?php

namespace Todo\Tests\Unit\Domain\User;

use PHPUnit\Framework\TestCase;
use Todo\Domain\User\UserId;

class UserIdTest extends TestCase
{
    public function testItCanEquals(): void
    {
        $id1 = UserId::generate();
        $id2 = UserId::generate();

        $this->assertTrue($id1->equals($id1));
        $this->assertFalse($id1->equals($id2));
    }
}
