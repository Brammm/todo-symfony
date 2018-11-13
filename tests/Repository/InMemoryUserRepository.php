<?php

declare(strict_types=1);

namespace Todo\Tests\Repository;

use Doctrine\DBAL\Driver\PDOException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Todo\Domain\EntityNotFoundException;
use Todo\Domain\User\User;
use Todo\Domain\User\UserId;
use Todo\Domain\User\UserRepository;

final class InMemoryUserRepository implements UserRepository
{
    /**
     * @var User[]
     */
    private $users = [];
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function save(User $newUser): void
    {
        foreach ($this->users as $user) {
            if ($newUser->email() === $user->email()) {
                throw new UniqueConstraintViolationException('user with email already exists', new PDOException(new \PDOException()));
            }
        }
        $this->users[(string) $newUser->id()] = $newUser;
        foreach ($newUser->releaseEvents() as $event) {
            $this->eventDispatcher->dispatch($event->getName(), $event);
        }
    }

    public function getById(UserId $userId): User
    {
        if (array_key_exists((string) $userId, $this->users)) {
            return $this->users[(string) $userId];
        }

        throw EntityNotFoundException::forEntityAndIdentifier('User', (string) $userId);
    }

    public function getByEmail(string $email): User
    {
        foreach ($this->users as $user) {
            if ($user->email() === $email) {
                return $user;
            }
        }

        throw EntityNotFoundException::forEntityAndIdentifier('User', $email);
    }

    public function findAll(): array
    {
        return array_values($this->users);
    }
}
