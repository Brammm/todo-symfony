<?php declare(strict_types=1);

namespace Todo\Application\User;

use Todo\Domain\User\User;
use Todo\Domain\User\UserRepository;

final class RegisterUserCommandHandler
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(RegisterUser $command): void
    {
        $user = new User(
            $command->userId(),
            $command->name(),
            $command->email(),
            password_hash($command->password(), PASSWORD_DEFAULT)
        );

        $this->userRepository->save($user);
    }
}
