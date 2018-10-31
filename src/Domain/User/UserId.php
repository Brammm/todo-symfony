<?php

declare(strict_types=1);

namespace Todo\Domain\User;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UserId
{
    /**
     * @var UuidInterface
     */
    private $uuid;

    private function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    public function toUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public static function fromUuid(UuidInterface $uuid): self
    {
        return new self($uuid);
    }

    public static function fromString(string $uuid): self
    {
        return new self(Uuid::fromString($uuid));
    }

    public function equals(UserId $that): bool
    {
        return $this->uuid->equals($that->uuid);
    }

    public function __toString(): string
    {
        return (string) $this->uuid;
    }
}
