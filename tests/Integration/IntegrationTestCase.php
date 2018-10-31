<?php

declare(strict_types=1);

namespace Todo\Tests\Integration;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class IntegrationTestCase extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function getEntityManager(): EntityManagerInterface
    {
        if (null === $this->entityManager) {
            self::bootKernel();

            $this->entityManager = self::$container->get(EntityManagerInterface::class);
        }

        return $this->entityManager;
    }

    public function flushAndClear(): void
    {
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();
    }

    protected function setUp(): void
    {
        $this->getEntityManager()->beginTransaction();
    }

    protected function tearDown(): void
    {
        $this->getEntityManager()->rollback();
    }
}
