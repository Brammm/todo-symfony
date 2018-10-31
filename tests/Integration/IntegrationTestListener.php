<?php

namespace Todo\Tests\Integration;

use Bootstrap;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;

class IntegrationTestListener implements TestListener
{
    use TestListenerDefaultImplementation;

    /**
     * @var Bootstrap
     */
    private $bootstrap;

    public function __construct()
    {
        $this->bootstrap = new Bootstrap();
    }

    public function startTest(Test $test): void
    {
        if ($test instanceof IntegrationTestCase) {
            $this->bootstrap->startTest($test);
        }
    }
}
