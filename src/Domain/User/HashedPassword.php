<?php declare(strict_types=1);

namespace Todo\Domain\User;

final class HashedPassword
{
    /**
     * @var string
     */
    private $hashedPassword;

    public function __construct(string $hashedPassword)
    {
        $this->hashedPassword = $hashedPassword;
    }

    public static function fromPassword(Password $password): self
    {
        return new self(password_hash((string) $password, PASSWORD_DEFAULT));
    }

    public function __toString(): string
    {
        return $this->hashedPassword;
    }

    public function matches(Password $password): bool
    {
        return password_verify((string) $password, $this->hashedPassword);
    }
}
