<?php

declare(strict_types=1);

namespace Todo\Infrastructure\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Todo\Domain\User\HashedPassword;
use Todo\Domain\User\UserId;

final class AuthenticatedUser implements UserInterface
{
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var string
     */
    private $email;

    /**
     * @var HashedPassword
     */
    private $hashedPassword;

    public function __construct(UserId $userId, string $email, HashedPassword $hashedPassword)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->hashedPassword = $hashedPassword;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return (string) $this->hashedPassword;
    }

    public function getHashedPassword(): HashedPassword
    {
        return $this->hashedPassword;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        return;
    }
}
