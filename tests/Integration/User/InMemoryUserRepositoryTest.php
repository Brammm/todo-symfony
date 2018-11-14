<?php

declare(strict_types=1);

namespace Todo\Tests\Integration\User;

use PHPUnit\Framework\TestCase;
use Todo\Domain\User\UserRepository;
use Todo\Tests\Repository\InMemoryUserRepository;

final class InMemoryUserRepositoryTest extends TestCase
{
    use UserRepositoryTest;

    /**
     * @var InMemoryUserRepository
     */
    private $repository;

    public function getRepository(): UserRepository
    {
        if (null === $this->repository) {
            $this->repository = new InMemoryUserRepository($this->getEventDispatcher());
        }

        return $this->repository;
    }
}
