<?php

require __DIR__ . '/../vendor/autoload.php';

use Doctrine\ORM\Tools\SchemaTool;
use Todo\Tests\Integration\IntegrationTestCase;

register_shutdown_function(function () {
    Bootstrap::endTestRun();
});

class Bootstrap
{
    /**
     * @var bool
     */
    private static $testDatabaseCreated = false;

    public function startTest(IntegrationTestCase $test): void
    {
        if (!self::$testDatabaseCreated) {
            $database = self::getDatabaseInformation()['database'];
            self::getPDO()->exec('DROP DATABASE IF EXISTS ' . $database);
            self::getPDO()->exec('CREATE DATABASE ' . $database);

            $em = $test->getEntityManager();
            $schemaTool = new SchemaTool($em);
            $schemaTool->createSchema($em->getMetadataFactory()->getAllMetadata());

            self::$testDatabaseCreated = true;
        }
    }

    public static function endTestRun(): void
    {
        if (!self::$testDatabaseCreated) {
            return;
        }

        self::getPDO()->exec('DROP DATABASE IF EXISTS ' . self::getDatabaseInformation()['database']);
    }

    private static function getPDO(): PDO
    {
        $info = self::getDatabaseInformation();

        return new PDO(
            \sprintf('mysql:host=%s;port=%s', $info['host'], $info['port']),
            $info['user'],
            $info['pass']
        );
    }

    private static function getDatabaseInformation(): array
    {
        $parsedUrl = parse_url(getenv('DATABASE_URL'));

        return [
            'host' => $parsedUrl['host'],
            'port' => $parsedUrl['port'],
            'user' => $parsedUrl['user'],
            'pass' => $parsedUrl['pass'],
            'database' => str_replace('/', '', $parsedUrl['path']),
        ];
    }
}
