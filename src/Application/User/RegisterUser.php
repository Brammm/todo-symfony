<?php

declare(strict_types=1);

namespace Todo\Application\User;

use Todo\Domain\User\Password;
use Todo\Domain\User\UserId;

final class RegisterUser
{
    /**
     * @var UserId
     */
    private $userId;

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

    public function __construct(UserId $userId, string $name, string $email, Password $password)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function userId(): UserId
    {
        return $this->userId;
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
