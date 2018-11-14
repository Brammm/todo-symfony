<?php

declare(strict_types=1);

namespace Todo\Infrastructure\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Todo\Domain\EntityNotFoundException;
use Todo\Domain\User\UserRepository;

final class AuthenticatedUserProvider implements UserProviderInterface
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loadUserByUsername($username)
    {
        try {
            $user = $this->userRepository->getByEmail($username);
        } catch (EntityNotFoundException $e) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }

        return new AuthenticatedUser(
            $user->id(),
            $user->email(),
            $user->hashedPassword()
        );
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof AuthenticatedUser) {
            throw new UnsupportedUserException();
        }

        try {
            $user = $this->userRepository->getById($user->getUserId());
        } catch (EntityNotFoundException $e) {
            throw new UsernameNotFoundException(sprintf('User with id "%s" not found.', $user->getUserId()));
        }

        return new AuthenticatedUser(
            $user->id(),
            $user->email(),
            $user->hashedPassword()
        );
    }

    public function supportsClass($class)
    {
        return $class === AuthenticatedUser::class;
    }
}