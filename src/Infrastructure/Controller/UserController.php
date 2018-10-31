<?php

declare(strict_types=1);

namespace Todo\Infrastructure\Controller;

use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Todo\Application\User\RegisterUser;
use Todo\Domain\User\Password;
use Todo\Domain\User\UserId;
use Todo\Infrastructure\Form\RegisterUserType;

final class UserController extends AbstractController
{
    /**
     * @Route("/user/register")
     */
    public function register(Request $request, CommandBus $commandBus): Response
    {
        $form = $this->createForm(RegisterUserType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $commandBus->handle(new RegisterUser(
                UserId::generate(),
                $data['name'],
                $data['email'],
                new Password($data['password'])
            ));

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
