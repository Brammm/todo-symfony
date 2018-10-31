<?php

namespace Todo\Tests\Unit\Domain\User;

use PHPUnit\Framework\TestCase;
use Todo\Domain\User\HashedPassword;
use Todo\Domain\User\Password;

class HashedPasswordTest extends TestCase
{
    public function testItCanMatchAPassword(): void
    {
        $fooPass = new Password('foo');
        $barPass = new Password('bar');

        $hashedPassword = HashedPassword::fromPassword($fooPass);

        $this->assertTrue($hashedPassword->matches($fooPass));
        $this->assertFalse($hashedPassword->matches($barPass));
    }

    public function testItHashesPassword(): void
    {
        $pass = new Password('foo');
        $hashedPassword = HashedPassword::fromPassword($pass);

        $this->assertNotEquals((string) $hashedPassword, 'foo');
        $this->assertTrue(password_verify('foo', (string) $hashedPassword));
    }
}
