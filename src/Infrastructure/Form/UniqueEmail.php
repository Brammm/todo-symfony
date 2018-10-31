<?php declare(strict_types=1);

namespace Todo\Infrastructure\Form;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class UniqueEmail extends Constraint
{
    public $message = 'A user with email "{{ string }}" is already registered.';
}
