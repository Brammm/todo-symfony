<?php

declare(strict_types=1);

namespace Todo\Tests\Integration\User;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Todo\Domain\EntityNotFoundException;
use Todo\Domain\User\Password;
use Todo\Domain\User\UserId;
use Todo\Domain\User\UserRepository;
use Todo\Tests\Builder\UserBuilder;

trait UserRepositoryTest
{
    abstract public function getRepository(): UserRepository;

    public function testItSavesAndGetsAUser(): void
    {
        $password = new Password('foo');

        $user = UserBuilder::withDefaults()
            ->withPassword($password)
            ->build();

        $this->getRepository()->save($user);

        $foundUser = $this->getRepository()->getById($user->id());

        $this->assertTrue($foundUser->id()->equals($user->id()));
        $this->assertTrue($foundUser->hashedPassword()->matches($password));
    }

    public function testItGetsUserByEmail(): void
    {
        $user = UserBuilder::withDefaults()->build();

        $this->getRepository()->save($user);

        $foundUser = $this->getRepository()->getByEmail('john@example.com');

        $this->assertTrue($foundUser->id()->equals($user->id()));
    }

    public function testItCantSaveUserWithSameEmailTwice(): void
    {
        $this->getRepository()->save(
            UserBuilder::withDefaults()->build()
        );

        $this->expectException(UniqueConstraintViolationException::class);
        $this->getRepository()->save(
            UserBuilder::withDefaults()->withId(UserId::fromString('c347eb41-9e7b-4b5d-93b7-07b9c522ecc4'))->build()
        );
    }

    public function testItThrowsExceptionIfItCantFindUserById(): void
    {
        $this->expectException(EntityNotFoundException::class);
        $this->getRepository()->getById(UserId::generate());
    }

    public function testItThrowsExceptionIfItCantFindUserByEmail(): void
    {
        $this->expectException(EntityNotFoundException::class);
        $this->getRepository()->getByEmail('john@example.com');
    }
}
