<?php declare(strict_types=1);

namespace Todo\Domain\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
final class User
{
    /**
     * @var string
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
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $passwordHash;
}
