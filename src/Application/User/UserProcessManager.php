<?php

declare(strict_types=1);

namespace Todo\Application\User;

use Symfony\Component\Messenger\MessageBusInterface;
use Todo\Domain\User\UserWasRegistered;

final class UserProcessManager
{
    /**
     * @var MessageBusInterface
     */
    private $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public function onUserWasRegistered(UserWasRegistered $event): void
    {
        $this->bus->dispatch(new SendWelcomeMail(
            $event->name(),
            $event->email(),
            $event->password()
        ));
    }
}
