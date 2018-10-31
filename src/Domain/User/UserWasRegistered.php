<?php declare(strict_types=1);

namespace Todo\Domain\User;

final class UserWasRegistered
{
    /**
     * @var UserId
     */
    private $userId;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }
}
