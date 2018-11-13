<?php

declare(strict_types=1);

namespace Todo\Tests\Integration\User;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
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

        assertTrue($foundUser->id()->equals($user->id()));
        assertTrue($foundUser->hashedPassword()->matches($password));
    }

    public function testItGetsUserByEmail(): void
    {
        $user = UserBuilder::withDefaults()->build();

        $this->getRepository()->save($user);

        $foundUser = $this->getRepository()->getByEmail('john@example.com');

        assertTrue($foundUser->id()->equals($user->id()));
    }

    public function testItCantSaveUserWithSameEmailTwice(): void
    {
        $this->getRepository()->save(
            UserBuilder::withDefaults()->build()
        );

        try {
            $this->getRepository()->save(
                UserBuilder::withDefaults()->withId(UserId::fromString('c347eb41-9e7b-4b5d-93b7-07b9c522ecc4'))->build()
            );
        } catch (Exception $e) {
            assertInstanceOf(UniqueConstraintViolationException::class, $e);
        }
    }

    public function testItThrowsExceptionIfItCantFindUserById(): void
    {
        try {
            $this->getRepository()->getById(UserId::generate());
        } catch (Exception $e) {
            assertInstanceOf(EntityNotFoundException::class, $e);
        }
    }

    public function testItThrowsExceptionIfItCantFindUserByEmail(): void
    {
        try {
            $this->getRepository()->getByEmail('john@example.com');
        } catch (Exception $e) {
            assertInstanceOf(EntityNotFoundException::class, $e);
        }
    }
}
