<?php

declare(strict_types=1);

namespace Todo\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Todo\Domain\EntityNotFoundException;
use Todo\Domain\User\User;
use Todo\Domain\User\UserId;
use Todo\Domain\User\UserRepository;

final class DoctrineUserRepository implements UserRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
    }

    public function getById(UserId $userId): User
    {
        $user = $this->repository->findOneBy([
            'id' => $userId->toUuid(),
        ]);

        if (!$user instanceof User) {
            throw EntityNotFoundException::forEntityAndIdentifier('User', (string) $userId);
        }

        return $user;
    }

    public function getByEmail(string $email): User
    {
        $user = $this->repository->findOneBy([
            'email' => $email,
        ]);

        if (!$user instanceof User) {
            throw EntityNotFoundException::forEntityAndIdentifier('User', $email);
        }

        return $user;
    }
}
