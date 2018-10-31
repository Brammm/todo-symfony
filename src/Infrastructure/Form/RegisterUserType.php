<?php

declare(strict_types=1);

namespace Todo\Infrastructure\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

final class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => new Length(['min' => 2]),
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email(),
                    new UniqueEmail(),
                ],
            ])
            ->add('password', PasswordType::class, [
                'constraints' => new Length(['min' => 2]),
            ])
            ->add('register', SubmitType::class);
    }
}
