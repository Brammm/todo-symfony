<?php

declare(strict_types=1);

namespace Todo\Application\User;

use Swift_Mailer;
use Swift_Message;
use Twig_Environment;

final class SendWelcomeMailCommandHandler
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var Twig_Environment
     */
    private $twig;

    public function __construct(Swift_Mailer $mailer, Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }
    
    public function handle(SendWelcomeMail $command): void
    {
        $message = (new Swift_Message('Hello Email'))
            ->setFrom('hello@bitcode.be', 'Bram from Todo')
            ->setTo($command->email())
            ->setBody(
                $this->twig->render(
                    'email/welcome.html.twig',
                    [
                        'name' => $command->name(),
                        'password' => (string) $command->password(),
                    ]
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }
}
