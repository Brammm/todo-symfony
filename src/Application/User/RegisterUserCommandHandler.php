<?php

declare(strict_types=1);

namespace Todo\Application\User;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Todo\Domain\User\User;
use Todo\Domain\User\UserRepository;

final class RegisterUserCommandHandler implements MessageHandlerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(RegisterUser $command): void
    {
        $user = User::register(
            $command->userId(),
            $command->name(),
            $command->email(),
            $command->password()
        );

        $this->userRepository->save($user);
    }
}
