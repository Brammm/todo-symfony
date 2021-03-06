<?php

declare(strict_types=1);

namespace Todo\Tests\Acceptance;

use Behat\Behat\Context\Context;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Todo\Application\User\RegisterUser;
use Todo\Domain\User\Password;
use Todo\Domain\User\User;
use Todo\Domain\User\UserId;
use Todo\Domain\User\UserRepository;
use Todo\Infrastructure\Kernel;

class FeatureContext implements Context
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yaml.
     */
    public function __construct()
    {
        $kernel = new Kernel('test', true);
        $kernel->boot();

        $this->container = $kernel->getContainer()->get('test.service_container');
    }

    /**
     * @When A user with email :email registers
     */
    public function aUserWithEmailRegisters(string $email)
    {
        $this->container->get(MessageBusInterface::class)->dispatch(new RegisterUser(
            UserId::generate(),
            'John',
            $email,
            new Password('test')
        ));
    }

    /**
     * @Then A user with email :email is present
     */
    public function aUserWithEmailIsPresent(string $email)
    {
        $user = $this->container->get(UserRepository::class)->getByEmail($email);

        assertInstanceOf(User::class, $user);
    }
}
