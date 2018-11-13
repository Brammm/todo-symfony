<?php

declare(strict_types=1);

namespace Todo\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(User::class);
        $this->eventDispatcher = $eventDispatcher;
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        foreach ($user->releaseEvents() as $event) {
            $this->eventDispatcher->dispatch($event->getName(), $event);
        }
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

    public function findAll(): array
    {
        return $this->repository->findAll();
    }
}
