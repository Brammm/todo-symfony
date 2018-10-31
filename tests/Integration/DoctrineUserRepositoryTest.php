<?php

declare(strict_types=1);

namespace Todo\Tests\Integration;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Todo\Domain\EntityNotFoundException;
use Todo\Domain\User\Password;
use Todo\Domain\User\UserId;
use Todo\Domain\User\UserRepository;
use Todo\Infrastructure\Repository\DoctrineUserRepository;
use Todo\Tests\Builder\UserBuilder;

final class DoctrineUserRepositoryTest extends IntegrationTestCase
{
    /**
     * @var UserRepository
     */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new DoctrineUserRepository(
            $this->getEntityManager(),
            $this->createMock(EventDispatcherInterface::class)
        );
    }

    public function testItSavesAndGetsAUser(): void
    {
        $password = new Password('foo');

        $user = UserBuilder::withDefaults()
            ->withPassword($password)
            ->build();

        $this->repository->save($user);
        $this->flushAndClear();

        $foundUser = $this->repository->getById($user->id());

        $this->assertTrue($foundUser->id()->equals($user->id()));
        $this->assertTrue($foundUser->hashedPassword()->matches($password));
    }

    public function testItGetsUserByEmail(): void
    {
        $user = UserBuilder::withDefaults()->build();

        $this->repository->save($user);
        $this->flushAndClear();

        $foundUser = $this->repository->getByEmail('john@example.com');

        $this->assertTrue($foundUser->id()->equals($user->id()));
    }

    public function testItCantSaveUserWithSameEmailTwice(): void
    {
        $this->repository->save(
            UserBuilder::withDefaults()->build()
        );

        $this->repository->save(
            UserBuilder::withDefaults()->withId(UserId::fromString('c347eb41-9e7b-4b5d-93b7-07b9c522ecc4'))->build()
        );
        $this->expectException(UniqueConstraintViolationException::class);
        $this->flushAndClear();
    }

    public function testItThrowsExceptionIfItCantFindUserById(): void
    {
        $this->expectException(EntityNotFoundException::class);
        $this->repository->getById(UserId::generate());
    }

    public function testItThrowsExceptionIfItCantFindUserByEmail(): void
    {
        $this->expectException(EntityNotFoundException::class);
        $this->repository->getByEmail('john@example.com');
    }
}
