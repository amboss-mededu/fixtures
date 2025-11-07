<?php

namespace TheIconic\Fixtures\Test\Persister\PDO;

use PHPUnit\Framework\TestCase;
use TheIconic\Fixtures\Persister\PDO\PersisterFactory;

class PersisterFactoryTest extends TestCase
{
    public function testCreate()
    {
        $persister = PersisterFactory::create(
            $_ENV['pdo_host'],
            $_ENV['pdo_database'],
            $_ENV['pdo_username'],
            $_ENV['pdo_password'],
            'mysql'
        );

        $this->assertInstanceOf('TheIconic\Fixtures\Persister\PDO\MysqlPersister', $persister);
    }

    public function testInvalidCreate()
    {
        $this->expectException(\TheIconic\Fixtures\Exception\PersisterException::class);

        PersisterFactory::create(
            $_ENV['pdo_host'],
            $_ENV['pdo_database'],
            $_ENV['pdo_username'],
            $_ENV['pdo_password'],
            'fakedb'
        );
    }
}
