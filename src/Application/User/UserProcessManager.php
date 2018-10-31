<?php

declare(strict_types=1);

namespace Todo\Application\User;

use League\Tactician\CommandBus;
use Todo\Domain\User\UserWasRegistered;

final class UserProcessManager
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function onUserWasRegistered(UserWasRegistered $event): void
    {
        $this->commandBus->handle(new SendWelcomeMail(
            $event->name(),
            $event->email(),
            $event->password()
        ));
    }
}
