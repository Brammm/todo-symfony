<?php

declare(strict_types=1);

namespace Todo\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Todo\Application\User\RegisterUser;
use Todo\Domain\User\Password;
use Todo\Domain\User\UserId;
use Todo\Domain\User\UserRepository;
use Todo\Infrastructure\Form\RegisterUserType;

final class UserController extends AbstractController
{
    /**
     * @Route("/users", name="users.list")
     */
    public function list(UserRepository $userRepository): Response
    {
        return $this->render('user/list.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/users/register", name="users.register")
     */
    public function register(Request $request, MessageBusInterface $bus): Response
    {
        $form = $this->createForm(RegisterUserType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $bus->dispatch(new RegisterUser(
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
