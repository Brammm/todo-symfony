<?php declare(strict_types=1);

namespace Todo\Tests\Integration;

use Todo\Domain\User\User;
use Todo\Domain\User\UserId;
use Todo\Domain\User\UserRepository;
use Todo\Infrastructure\Repository\DoctrineUserRepository;

final class DoctrineUserRepositoryTest extends IntegrationTestCase
{
    /**
     * @var UserRepository
     */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new DoctrineUserRepository($this->getEntityManager());
    }

    public function testItSavesAndGetsAUser(): void
    {
        $id = UserId::generate();

        $user = new User(
            $id,
            'John Doe',
            'john@example.com',
            'foo'
        );

        $this->repository->save($user);

        $this->flushAndClear();

        $foundUser = $this->repository->getById($user->id());

        $this->assertTrue($foundUser->id()->equals($id));
    }
}
