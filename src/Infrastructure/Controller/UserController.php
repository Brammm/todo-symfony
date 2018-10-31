<?php

declare(strict_types=1);

namespace Todo\Infrastructure\Controller;

use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Todo\Application\User\RegisterUser;
use Todo\Domain\User\UserId;

final class UserController extends AbstractController
{
    /**
     * @Route("/user/register")
     */
    public function register(Request $request, CommandBus $commandBus): Response
    {
        $form = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('register', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $commandBus->handle(new RegisterUser(
                UserId::generate(),
                $data['name'],
                $data['email'],
                $data['password']
            ));

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
