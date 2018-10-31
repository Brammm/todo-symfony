<?php declare(strict_types=1);

namespace Todo\Infrastructure\Form;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Todo\Domain\EntityNotFoundException;
use Todo\Domain\User\UserRepository;

final class UniqueEmailValidator extends ConstraintValidator
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        try {
            $this->userRepository->getByEmail($value);
        } catch (EntityNotFoundException $exception) {
            return;
        }

        $this->context->addViolation($constraint->message, [
            '{{ string }}' => $value,
        ]);
    }
}
