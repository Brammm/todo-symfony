<?php

namespace Todo\Tests\Unit\Infrastructure\Form;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Todo\Domain\EntityNotFoundException;
use Todo\Domain\User\UserRepository;
use Todo\Infrastructure\Form\UniqueEmail;
use Todo\Infrastructure\Form\UniqueEmailValidator;
use Todo\Tests\Builder\UserBuilder;

class UniqueEmailValidatorTest extends TestCase
{
    /**
     * @var UserRepository|MockObject
     */
    private $userRepository;

    /**
     * @var ExecutionContextInterface|MockObject
     */
    private $context;

    /**
     * @var UniqueEmailValidator
     */
    private $validator;

    protected function setUp(): void
    {
        $this->context = $this->createMock(ExecutionContextInterface::class);
        $this->userRepository = $this->createMock(UserRepository::class);

        $this->validator = new UniqueEmailValidator($this->userRepository);
        $this->validator->initialize($this->context);
    }

    public function testItAddsViolationIfUserIsFound(): void
    {
        $this->userRepository->expects($this->once())
            ->method('getByEmail')
            ->with('john@example.com')
            ->willReturn(UserBuilder::withDefaults()->build());

        $this->context->expects($this->once())
            ->method('addViolation');

        $this->validator->validate('john@example.com', new UniqueEmail());
    }

    public function testItReturnsNullWhenNoUserFound(): void
    {
        $this->userRepository->expects($this->once())
            ->method('getByEmail')
            ->with('john@example.com')
            ->willThrowException(new EntityNotFoundException());

        $this->context->expects($this->never())
            ->method('addViolation');

        $this->validator->validate('john@example.com', new UniqueEmail());
    }
}
