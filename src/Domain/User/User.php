<?php

declare(strict_types=1);

namespace Todo\Domain\User;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Todo\Domain\Aggregate;

/**
 * @ORM\Entity
 */
final class User extends Aggregate
{
    /**
     * @var Uuid
     *
     * @ORM\Id
     * @ORM\Column(type="uuid_binary")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $hashedPassword;

    private function __construct()
    {
    }

    public static function register(UserId $id, string $name, string $email, Password $password): self
    {
        $user = new self();

        $user->id = $id->toUuid();
        $user->name = $name;
        $user->email = $email;
        $user->hashedPassword = (string) HashedPassword::fromPassword($password);

        $user->recordThat(new UserWasRegistered(
            $user->id(),
            $name,
            $email,
            $password
        ));

        return $user;
    }

    public function id(): UserId
    {
        return UserId::fromUuid($this->id);
    }

    public function hashedPassword(): HashedPassword
    {
        return new HashedPassword($this->hashedPassword);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }
}
