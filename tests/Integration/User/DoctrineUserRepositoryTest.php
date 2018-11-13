<?php

declare(strict_types=1);

namespace Todo\Tests\Integration\User;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Todo\Domain\User\UserRepository;
use Todo\Infrastructure\Repository\DoctrineUserRepository;
use Todo\Tests\Integration\IntegrationTestCase;

final class DoctrineUserRepositoryTest extends IntegrationTestCase
{
    use UserRepositoryTest;

    /**
     * @var DoctrineUserRepository
     */
    private $repository;

    public function getRepository(): UserRepository
    {
        if ($this->repository === null) {
            $this->repository =  new DoctrineUserRepository(
                $this->getEntityManager(),
                $this->createMock(EventDispatcherInterface::class)
            );
        }

        return $this->repository;
    }
}