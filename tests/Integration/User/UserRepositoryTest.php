<?php

declare(strict_types=1);

namespace Todo\Tests\Integration\User;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Todo\Domain\EntityNotFoundException;
use Todo\Domain\User\Password;
use Todo\Domain\User\UserId;
use Todo\Domain\User\UserRepository;
use Todo\Domain\User\UserWasRegistered;
use Todo\Tests\Builder\UserBuilder;
use Todo\Tests\Integration\LoggingEventDispatcher;

trait UserRepositoryTest
{
    /**
     * @var LoggingEventDispatcher
     */
    private $eventDispatcher;

    abstract public function getRepository(): UserRepository;

    public function getEventDispatcher(): LoggingEventDispatcher
    {
        if ($this->eventDispatcher === null) {
            $this->eventDispatcher = new LoggingEventDispatcher();
        }

        return $this->eventDispatcher;
    }

    public function testItDispatchesEventsOnSave()
    {
        $user = UserBuilder::withDefaults()
            ->withPassword(new Password('foo'))
            ->build();

        $this->getRepository()->save($user);

        $events = $this->getEventDispatcher()->getDispatchedEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(UserWasRegistered::class, array_shift($events));
    }

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
