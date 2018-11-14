<?php

declare(strict_types=1);

namespace Todo\Infrastructure\Messenger;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;

final class DoctrineTransactionalMiddleware implements MiddlewareInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param object $message
     *
     * @return mixed
     */
    public function handle($message, callable $next)
    {
        $this->entityManager->beginTransaction();
        try {
            $result = $next($message);
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
        } catch (\Throwable $t) {
            $this->entityManager->rollback();
        }

        return $result;
    }
}
