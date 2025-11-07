<?php

namespace TheIconic\Fixtures\Test\FixtureManager;

use PHPUnit\Framework\TestCase;
use Redis;
use TheIconic\Fixtures\FixtureManager\FixtureManager;

class FixtureManagerRedisTest extends TestCase
{
    private $fixtures = [
        'customer_address_region_suburb.xml',
        'country_region.yml',
    ];

    private $conn;

    private function getConnection() {
        if ($this->conn === null) {
            $redis = new Redis();
            $redis->connect($_ENV['redis_host'], $_ENV['redis_port']);
            $redis->select($_ENV['redis_db_number']);
            $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
            $redis->setOption(Redis::OPT_PREFIX, $_ENV['redis_namespace']);

            $this->conn = $redis;
        }

        return $this->conn;
    }

    public function tearDown(): void
    {
        $this->conn;
    }

    public function testFixtureManagerPersist()
    {
        $fixtures = array_map(function ($val) {
            return './tests/Support/TestsFixtures/' . $val;
        }, $this->fixtures);

        $fixtureManager = FixtureManager::create($fixtures);

        $fixtureManager
            ->setDefaultRedisPersister(
                $_ENV['redis_host'],
                $_ENV['redis_port'],
                $_ENV['redis_db_number'],
                $_ENV['redis_namespace'],
                $_ENV['redis_namespace_separator'],
                $_ENV['redis_serializer']
            )
            ->persist();

        $countryRegion = $this->getConnection()->get('country_region');
        $customerAddressRegionSuburb = $this->getConnection()->get('customer_address_region_suburb');

        $this->assertEquals(8, count($countryRegion));
        $this->assertEquals(16644, count($customerAddressRegionSuburb));
    }

    public function testFixtureManagerIllegalPersist()
    {
        $this->expectException(\RuntimeException::class);

        $fixtureManager = FixtureManager::create('./tests/Support/TestsFixtures/country_region.yml');

        $fixtureManager->persist();
    }

    public function testFixtureManagerInvalidSource()
    {
        $this->expectException(\TheIconic\Fixtures\Exception\SourceNotFoundException::class);

        FixtureManager::create('fake.xml');
    }

    public function testFixtureManagerClean()
    {
        $fixtures = array_map(function ($val) {
            return './tests/Support/TestsFixtures/' . $val;
        }, $this->fixtures);

        $fixtureManager = FixtureManager::create($fixtures);

        $fixtureManager
            ->setDefaultRedisPersister(
                $_ENV['redis_host'],
                $_ENV['redis_port'],
                $_ENV['redis_db_number'],
                $_ENV['redis_namespace'],
                $_ENV['redis_namespace_separator'],
                $_ENV['redis_serializer']
            )
            ->persist()
            ->cleanStorage();

        $countryRegion = $this->getConnection()->get('country_region');
        $customerAddressRegionSuburb = $this->getConnection()->get('customer_address_region_suburb');

        $this->assertFalse($countryRegion);
        $this->assertFalse($customerAddressRegionSuburb);
    }
}
