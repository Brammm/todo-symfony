<?php

declare(strict_types=1);

namespace Todo\Domain\User;

interface UserRepository
{
    public function save(User $user): void;

    public function getById(UserId $userId): User;

    public function getByEmail(string $email): User;

    /**
     * @return User[]
     */
    public function findAll(): array;
}
