<?php

declare(strict_types=1);

namespace Todo\Domain\User;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 */
final class User
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
    private $passwordHash;

    public function __construct(UserId $id, string $name, string $email, string $passwordHash)
    {
        $this->id = $id->toUuid();
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    public function id(): UserId
    {
        return UserId::fromUuid($this->id);
    }
}
