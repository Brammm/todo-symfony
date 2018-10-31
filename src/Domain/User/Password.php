<?php declare(strict_types=1);

namespace Todo\Domain\User;

use InvalidArgumentException;

final class Password
{
    /**
     * @var string
     */
    private $password;

    public function __construct(string $password)
    {
        if (empty($password)) {
            throw new InvalidArgumentException('Password can\'t be empty.');
        }

        $this->password = $password;
    }

    public function __toString(): string
    {
        return $this->password;
    }
}
