<?php

declare(strict_types=1);

namespace Todo\Tests\Unit\Domain\User;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Todo\Domain\User\Password;

final class PasswordTest extends TestCase
{
    public function testItCantBeEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        new Password('');
    }
}
