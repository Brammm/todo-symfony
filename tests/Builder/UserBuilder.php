<?php

declare(strict_types=1);

namespace Todo\Tests\Builder;

use Todo\Domain\User\HashedPassword;
use Todo\Domain\User\Password;
use Todo\Domain\User\User;
use Todo\Domain\User\UserId;

final class UserBuilder
{
    /**
     * @var UserId
     */
    private $id;

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

    public static function withDefaults(): self
    {
        $builder = new self();
        $builder->id = UserId::fromString('03b43613-599b-47f3-b35e-38b5f6401c66');
        $builder->name = 'John Doe';
        $builder->email = 'john@example.com';
        $builder->password = new Password('pass');

        return $builder;
    }

    public function withId(UserId $userId): self
    {
        $builder = clone $this;
        $builder->id = $userId;

        return $builder;
    }

    public function withPassword(Password $password): self
    {
        $builder = clone $this;
        $builder->password = $password;

        return $builder;
    }

    public function build(): User
    {
        return User::register(
            $this->id,
            $this->name,
            $this->email,
            HashedPassword::fromPassword($this->password)
        );
    }
}
