<?php

declare(strict_types=1);

namespace Todo\Tests\Repository;

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

    public function save(User $user): void
    {
        $this->users[(string) $user->id()] = $user;
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

        throw EntityNotFoundException::forEntityAndIdentifier('User', (string) $userId);
    }

    public function findAll(): array
    {
        return array_values($this->users);
    }
}
