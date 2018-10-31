<?php

declare(strict_types=1);

namespace Todo\Application\User;

use Todo\Domain\User\Password;

final class SendWelcomeMail
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var Password
     */
    private $password;

    public function __construct(string $name, string $email, Password $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): Password
    {
        return $this->password;
    }
}
